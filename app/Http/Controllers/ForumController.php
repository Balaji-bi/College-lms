<?php

namespace App\Http\Controllers;

use App\Models\ForumMessage;
use App\Models\ForumMessageLike;
use App\Events\NewForumMessage;
use App\Events\ForumMessageLiked;
use App\Events\ForumMessageDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ForumController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the forum view
     */
    public function index()
    {
        $messages = ForumMessage::with(['user', 'likes', 'replyTo.user'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('forum.index', compact('messages'));
    }

    /**
     * Store a new message
     */
    public function storeMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'nullable|string|max:1000',
                'image' => 'nullable|image|max:5120',
                'reply_to_id' => 'nullable|exists:forum_messages,id',
            ]);

            if (empty($validated['message']) && !$request->hasFile('image')) {
                return response()->json(['error' => 'Please provide a message or an image.'], 422);
            }

            $message = new ForumMessage();
            $message->user_id = Auth::id();
            $message->reply_to_id = $validated['reply_to_id'] ?? null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('forum_images', 'public');
                $message->type = 'image';
                $message->file_path = $path;
            } else {
                $message->type = 'text';
                $message->message = $validated['message'];
            }

            $message->save();

            $message->load(['user', 'replyTo.user']);

            broadcast(new NewForumMessage($message))->toOthers();

            return response()->json($message); // ✅ Ensure this stays JSON
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500); // 👈 return the actual exception as JSON
        }
    }


    /**
     * Like or unlike a message
     */
    public function toggleLike(Request $request, $messageId)
    {
        $message = ForumMessage::findOrFail($messageId);
        $userId = Auth::id();
        
        // Check if user already liked the message
        $like = ForumMessageLike::where('user_id', $userId)
            ->where('message_id', $messageId)
            ->first();
            
        if ($like) {
            // Unlike
            $like->delete();
            $action = 'unliked';
        } else {
            // Like
            ForumMessageLike::create([
                'user_id' => $userId,
                'message_id' => $messageId
            ]);
            $action = 'liked';
        }
        
        // Reload message with relationships
        $message->load('likes.user');
        
        // Broadcast like status change
        broadcast(new ForumMessageLiked($message, $userId, $action))->toOthers();
        
        return response()->json([
            'action' => $action,
            'message' => $message,
            'likes_count' => $message->likes()->count()
        ]);
    }

    /**
     * Delete a message (only if it belongs to current user)
     */
    public function deleteMessage($messageId)
    {
        $message = ForumMessage::findOrFail($messageId);
        
        // Check if the authenticated user owns the message
        if ($message->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // If message has an image, delete it from storage
        if ($message->type === 'image' && $message->file_path) {
            Storage::disk('public')->delete($message->file_path);
        }
        
        // Delete the message
        $message->delete();
        
        // Broadcast the deletion
        broadcast(new ForumMessageDeleted($messageId))->toOthers();
        
        return response()->json(['success' => true]);
    }
}
