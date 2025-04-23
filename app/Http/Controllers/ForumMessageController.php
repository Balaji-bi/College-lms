<?php
// app/Http/Controllers/ForumMessageController.php
namespace App\Http\Controllers;

use App\Models\ForumMessage;
use App\Events\NewMessage;
use App\Events\MessageLiked;
use App\Events\MessageDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ForumMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Get all messages grouped by date
        $messagesQuery = ForumMessage::with(['user', 'replyTo.user'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        $messages = $messagesQuery->groupBy(function($message) {
            return Carbon::parse($message->created_at)->format('F d, Y');
        });
        
        return view('forum.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png|max:5120', // 5MB max
            'reply_to_id' => 'nullable|exists:forum_messages,id',
        ]);
        
        // Check if at least message or image is provided
        if (!$request->filled('message') && !$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'message' => 'Message or image is required.'
            ], 422);
        }
        
        $imagePath = null;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('forum_images', 'public');
        }
        
        $message = ForumMessage::create([
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'image_path' => $imagePath,
            'reply_to_id' => $request->reply_to_id,
            'liked_by' => json_encode([]),
        ]);
        
        // Load relationships for broadcast
        $message->load(['user', 'replyTo.user']);
        
        // Broadcast to all users
        broadcast(new NewMessage($message))->toOthers();
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    
    // app/Http/Controllers/ForumMessageController.php (continued)
    public function like($id)
    {
        $message = ForumMessage::findOrFail($id);
        $likedBy = json_decode($message->liked_by, true) ?? [];
        $userId = Auth::id();
        
        // Toggle like status
        if (in_array($userId, $likedBy)) {
            $likedBy = array_diff($likedBy, [$userId]);
        } else {
            $likedBy[] = $userId;
        }
        
        $message->liked_by = json_encode($likedBy);
        $message->save();
        
        // Broadcast to all users
        broadcast(new MessageLiked($message->id, $likedBy))->toOthers();
        
        return response()->json([
            'success' => true,
            'liked_by' => $likedBy
        ]);
    }
    
    public function destroy($id)
    {
        $message = ForumMessage::findOrFail($id);
        
        // Check if the user is the sender
        if ($message->sender_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }
        
        // Delete associated image if exists
        if ($message->image_path) {
            Storage::disk('public')->delete($message->image_path);
        }
        
        $message->delete();
        
        // Broadcast to all users
        broadcast(new MessageDeleted($id))->toOthers();
        
        return response()->json([
            'success' => true
        ]);
    }
}

