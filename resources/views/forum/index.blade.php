<!-- resources/views/forum/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forum - College LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])  
    <style>
        /* iOS-style Minimalist Gray Theme */
        .container {
            max-width: 768px;
            width: 100%;
            box-sizing: border-box;
            margin: auto;
            border-width: 0;
            border-style: solid;
        }
        .forum-container {
            max-width: 100%;
            height: calc(100vh - 120px);
            background-color:rgb(203, 169, 169);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Message list container */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        /* Message input area */
        .message-input-container {
            background-color: #ffffff;
            border-top: 1px solid #e0e0e0;
            padding: 12px;
            display: flex;
            align-items: center;
        }

        .message-input {
            flex: 1;
            border: 1px solid #d1d1d1;
            border-radius: 24px;
            padding: 8px 15px;
            font-size: 14px;
            resize: none;
            max-height: 120px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        .message-input:focus {
            outline: none;
            border-color: #a0a0a0;
            background-color: #ffffff;
        }

        .btn-send {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-send:hover {
            background-color: #0069d9;
        }

        .btn-image {
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-image:hover {
            background-color: #5a6268;
        }

        /* Message bubbles */
        .message {
            display: flex;
            margin-bottom: 15px;
            position: relative;
        }

        .message.own-message {
            justify-content: flex-end;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .message.own-message .avatar {
            order: 2;
            margin-right: 0;
            margin-left: 8px;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .message-bubble {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 18px;
            position: relative;
        }

        .message:not(.own-message) .message-bubble {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-top-left-radius: 4px;
        }

        .message.own-message .message-bubble {
            background-color: #e1f5fe;
            border: 1px solid #c8e6ff;
            border-top-right-radius: 4px;
        }

        .message-content {
            word-break: break-word;
        }

        /* Username and timestamp */
        .message-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 12px;
        }

        .username {
            font-weight: bold;
            color: #555;
        }

        .timestamp {
            color: #999;
        }

        /* Image messages */
        .message-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 12px;
            cursor: pointer;
        }

        /* Message options dropdown/context menu */
        .message-options {
            position: absolute;
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 8px 0;
            z-index: 1000;
            display: none;
        }

        .message-options.active {
            display: block;
        }

        .message-option {
            padding: 8px 16px;
            cursor: pointer;
            white-space: nowrap;
            font-size: 14px;
            color: #333;
        }

        .message-option:hover {
            background-color: #f5f5f5;
        }

        /* Reply indicator */
        .reply-to {
            background-color: rgba(0,0,0,0.03);
            border-left: 3px solid #007bff;
            padding: 5px 10px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 13px;
        }

        .reply-to strong {
            color: #007bff;
        }

        /* Likes */
        .like-count {
            font-size: 12px;
            color: #777;
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .like-count.liked {
            color: #e53935;
        }

        .like-heart {
            margin-right: 4px;
        }

        /* Hidden file input */
        .file-input {
            display: none;
        }

        /* Reply mode indicator */
        .reply-indicator {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-top: 1px solid #e0e0e0;
            font-size: 13px;
            display: none;
        }

        .reply-indicator.active {
            display: flex;
            justify-content: space-between;
        }

        .reply-indicator span {
            flex: 1;
        }

        .cancel-reply {
            color: #dc3545;
            cursor: pointer;
            font-weight: bold;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>

</head>
<body class="bg-gray-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Forum Chat') }}</div>

                    <div class="card-body p-0">
                        <div class="forum-container">
                            <!-- Messages container -->
                            <div class="messages-container" id="messages-container">
                                @if(count($messages) > 0)
                                    @foreach($messages as $message)
                                        <div class="message {{ Auth::id() == $message->user_id ? 'own-message' : '' }}" 
                                            data-message-id="{{ $message->id }}" 
                                            data-user-id="{{ $message->user_id }}">
                                            <div class="avatar">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($message->user->name) }}&background=random" alt="{{ $message->user->name }}">
                                            </div>
                                            <div class="message-bubble">
                                                <div class="message-meta">
                                                    <span class="username">{{ $message->user->name }}</span>
                                                    <span class="timestamp">{{ $message->created_at->format('h:i A') }}</span>
                                                </div>
                                                
                                                @if($message->reply_to_id)
                                                    <div class="reply-to">
                                                        <strong>{{ $message->replyTo->user->name }}</strong>: 
                                                        {{ $message->replyTo->type == 'text' ? 
                                                            (strlen($message->replyTo->message) > 30 ? substr($message->replyTo->message, 0, 30) . '...' : $message->replyTo->message) : 
                                                            'Image' }}
                                                    </div>
                                                @endif
                                                
                                                <div class="message-content">
                                                    @if($message->type == 'text')
                                                        {{ $message->message }}
                                                    @else
                                                        <img src="{{ asset('storage/' . $message->file_path) }}" class="message-image" alt="Shared image">
                                                    @endif
                                                </div>
                                                
                                                <div class="like-count {{ $message->isLikedByUser(Auth::id()) ? 'liked' : '' }}">
                                                    <span class="like-heart">
                                                        <i class="fa {{ $message->isLikedByUser(Auth::id()) ? 'fa-heart' : 'fa-heart-o' }}"></i>
                                                    </span>
                                                    <span class="likes-text">
                                                        {{ $message->likes_count }} likes
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fa fa-comments-o"></i>
                                        </div>
                                        <p>No messages yet. Be the first to start the conversation!</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Reply indicator -->
                            <div class="reply-indicator" id="reply-indicator">
                                <span>Replying to <strong id="reply-to-username"></strong></span>
                                <span class="cancel-reply" id="cancel-reply">Cancel</span>
                            </div>
                            
                            <!-- Message input -->
                            <div class="message-input-container">
                                <textarea class="message-input" id="message-input" placeholder="Type a message..." rows="1"></textarea>
                                <input type="file" id="file-input" class="file-input" accept="image/*">
                                <button class="btn-image" id="btn-image">
                                    <i class="fa fa-image"></i>
                                </button>
                                <button class="btn-send" id="btn-send">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Context Menu for Messages -->
    <div class="message-options" id="message-options">
        <div class="message-option" id="option-reply">Reply</div>
        <div class="message-option" id="option-copy">Copy</div>
        <div class="message-option" id="option-like">Like</div>
        <div class="message-option" id="option-delete">Delete</div>
    </div>
</body>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        const messagesContainer = document.getElementById('messages-container');
        const messageInput = document.getElementById('message-input');
        const btnSend = document.getElementById('btn-send');
        const btnImage = document.getElementById('btn-image');
        const fileInput = document.getElementById('file-input');
        const messageOptions = document.getElementById('message-options');
        const optionReply = document.getElementById('option-reply');
        const optionCopy = document.getElementById('option-copy');
        const optionLike = document.getElementById('option-like');
        const optionDelete = document.getElementById('option-delete');
        const replyIndicator = document.getElementById('reply-indicator');
        const replyToUsername = document.getElementById('reply-to-username');
        const cancelReply = document.getElementById('cancel-reply');
        
        let currentUserId = {{ Auth::id() }};
        let replyToMessageId = null;
        let lastClickedMessage = null;
        
        // Auto-resize textarea as user types
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Scroll to bottom of messages on page load
        scrollToBottom();
        
        // Send message when send button is clicked
        btnSend.addEventListener('click', sendTextMessage);
        
        // Or when Enter key is pressed (without Shift)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendTextMessage();
            }
        });
        
        // Image upload button click
        btnImage.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Handle image file selection
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                sendImageMessage(this.files[0]);
            }
        });
        
        // Context menu functionality
        document.addEventListener('click', function(e) {
            // Hide context menu when clicking outside
            if (!messageOptions.contains(e.target)) {
                messageOptions.classList.remove('active');
            }
        });
        
        // Right-click on messages
        messagesContainer.addEventListener('contextmenu', function(e) {
            if (e.target.closest('.message')) {
                e.preventDefault();
                const messageElement = e.target.closest('.message');
                const messageId = messageElement.dataset.messageId;
                const userId = messageElement.dataset.userId;
                
                // Store reference to clicked message
                lastClickedMessage = messageElement;
                
                // Show/hide options based on message ownership
                if (userId == currentUserId) {
                    // Own message
                    optionLike.style.display = 'none';
                    optionDelete.style.display = 'block';
                } else {
                    // Other's message
                    optionLike.style.display = 'block';
                    optionDelete.style.display = 'none';
                }
                
                // Position and show context menu
                messageOptions.style.top = e.pageY + 'px';
                messageOptions.style.left = e.pageX + 'px';
                messageOptions.classList.add('active');
                messageOptions.dataset.messageId = messageId;
            }
        });
        
        // Context menu options
        optionReply.addEventListener('click', function() {
            const messageId = messageOptions.dataset.messageId;
            const messageEl = document.querySelector(`.message[data-message-id="${messageId}"]`);
            const username = messageEl.querySelector('.username').textContent;
            
            // Set reply mode
            replyToMessageId = messageId;
            replyToUsername.textContent = username;
            replyIndicator.classList.add('active');
            
            // Focus input
            messageInput.focus();
            
            // Hide context menu
            messageOptions.classList.remove('active');
        });
        
        optionCopy.addEventListener('click', function() {
            const messageId = messageOptions.dataset.messageId;
            const messageEl = document.querySelector(`.message[data-message-id="${messageId}"]`);
            const messageContent = messageEl.querySelector('.message-content').textContent.trim();
            
            // Copy to clipboard
            navigator.clipboard.writeText(messageContent)
                .then(() => {
                    // Show a brief notification (optional)
                    const notification = document.createElement('div');
                    notification.style.position = 'fixed';
                    notification.style.bottom = '20px';
                    notification.style.left = '50%';
                    notification.style.transform = 'translateX(-50%)';
                    notification.style.padding = '8px 16px';
                    notification.style.backgroundColor = 'rgba(0,0,0,0.7)';
                    notification.style.color = 'white';
                    notification.style.borderRadius = '4px';
                    notification.style.zIndex = '9999';
                    notification.innerText = 'Text copied to clipboard';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 2000);
                });
            
            // Hide context menu
            messageOptions.classList.remove('active');
        });
        
        optionLike.addEventListener('click', function() {
            const messageId = messageOptions.dataset.messageId;
            toggleLike(messageId);
            messageOptions.classList.remove('active');
        });
        
        optionDelete.addEventListener('click', function() {
            const messageId = messageOptions.dataset.messageId;
            deleteMessage(messageId);
            messageOptions.classList.remove('active');
        });
        
        // Cancel reply button
        cancelReply.addEventListener('click', function() {
            replyToMessageId = null;
            replyIndicator.classList.remove('active');
        });
        
        // Like message when clicking on the like count area
        messagesContainer.addEventListener('click', function(e) {
            if (e.target.closest('.like-count') || e.target.closest('.like-heart')) {
                const messageElement = e.target.closest('.message');
                const messageId = messageElement.dataset.messageId;
                toggleLike(messageId);
            }
        });
        
        // Main Functions
        
        function sendTextMessage() {
            const message = messageInput.value.trim();
            if (!message) return;
            
            const formData = new FormData();
            formData.append('message', message);
            
            if (replyToMessageId) {
                formData.append('reply_to_id', replyToMessageId);
            }
            
            fetch('{{ route("forum.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Clear input field and reset reply mode
                messageInput.value = '';
                messageInput.style.height = 'auto';
                replyToMessageId = null;
                replyIndicator.classList.remove('active');
                
                // Append message to UI (if not caught by broadcast)
                appendMessage(data);
                scrollToBottom();
            })
            .catch(error => console.error('Error sending message:', error));
        }
        
        function sendImageMessage(file) {
            const formData = new FormData();
            formData.append('image', file);
            
            if (replyToMessageId) {
                formData.append('reply_to_id', replyToMessageId);
            }
            
            fetch('{{ route("forum.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Reset file input and reply mode
                fileInput.value = '';
                replyToMessageId = null;
                replyIndicator.classList.remove('active');
                
                // Append message to UI (if not caught by broadcast)
                appendMessage(data);
                scrollToBottom();
            })
            .catch(error => console.error('Error sending image:', error));
        }
        
        function toggleLike(messageId) {
            fetch(`/forum/message/${messageId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update UI locally
                const messageElement = document.querySelector(`.message[data-message-id="${messageId}"]`);
                const likesElement = messageElement.querySelector('.like-count');
                const heartIcon = likesElement.querySelector('.like-heart i');
                const likesText = likesElement.querySelector('.likes-text');
                
                if (data.action === 'liked') {
                    likesElement.classList.add('liked');
                    heartIcon.classList.remove('fa-heart-o');
                    heartIcon.classList.add('fa-heart');
                } else {
                    likesElement.classList.remove('liked');
                    heartIcon.classList.remove('fa-heart');
                    heartIcon.classList.add('fa-heart-o');
                }
                
                likesText.textContent = `${data.likes_count} likes`;
            })
            .catch(error => console.error('Error toggling like:', error));
        }
        
        function deleteMessage(messageId) {
            if (confirm('Are you sure you want to delete this message?')) {
                fetch(`/forum/message/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove message from UI
                        const messageElement = document.querySelector(`.message[data-message-id="${messageId}"]`);
                        messageElement.remove();
                    }
                })
                .catch(error => console.error('Error deleting message:', error));
            }
        }
        
        function appendMessage(message) {
            // Remove empty state if present
            const emptyState = messagesContainer.querySelector('.empty-state');
            if (emptyState) {
                emptyState.remove();
            }
            
            // Create message element
            const messageEl = document.createElement('div');
            messageEl.className = `message ${message.user_id == currentUserId ? 'own-message' : ''}`;
            messageEl.dataset.messageId = message.id;
            messageEl.dataset.userId = message.user.id;
            
            // Construct message HTML
            messageEl.innerHTML = `
                <div class="avatar">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(message.user.name)}&background=random" alt="${message.user.name}">
                </div>
                <div class="message-bubble">
                    <div class="message-meta">
                        <span class="username">${message.user.name}</span>
                        <span class="timestamp">${formatTime(new Date(message.created_at))}</span>
                    </div>
                    
                    ${message.reply_to_id ? `
                        <div class="reply-to">
                            <strong>${message.reply_to.user.name}</strong>: 
                            ${message.reply_to.type == 'text' ? 
                                (message.reply_to.message.length > 30 ? message.reply_to.message.substring(0, 30) + '...' : message.reply_to.message) : 
                                'Image'}
                        </div>
                    ` : ''}
                    
                    <div class="message-content">
                        ${message.type == 'text' ? 
                            message.message : 
                            `<img src="/storage/${message.file_path}" class="message-image" alt="Shared image">`}
                    </div>
                    
                    <div class="like-count">
                        <span class="like-heart">
                            <i class="fa fa-heart-o"></i>
                        </span>
                        <span class="likes-text">0 likes</span>
                    </div>
                </div>
            `;
            
            // Append to container
            messagesContainer.appendChild(messageEl);
        }
        
        function formatTime(date) {
            return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        }
        
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Real-time with Pusher
        // Replace with your Pusher key
        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true
        });
        
        const channel = pusher.subscribe('forum');
        
        // Listen for new messages
        channel.bind('App\\Events\\NewForumMessage', function(data) {
            appendMessage(data.message);
            scrollToBottom();
        });
        
        // Listen for likes
        channel.bind('App\\Events\\ForumMessageLiked', function(data) {
            const messageElement = document.querySelector(`.message[data-message-id="${data.message.id}"]`);
            if (messageElement) {
                const likesElement = messageElement.querySelector('.like-count');
                const heartIcon = likesElement.querySelector('.like-heart i');
                const likesText = likesElement.querySelector('.likes-text');
                
                // Update likes count
                likesText.textContent = `${data.message.likes.length} likes`;
                
                // Update heart for current user if necessary
                if (data.userId == currentUserId) {
                    if (data.action === 'liked') {
                        likesElement.classList.add('liked');
                        heartIcon.classList.remove('fa-heart-o');
                        heartIcon.classList.add('fa-heart');
                    } else {
                        likesElement.classList.remove('liked');
                        heartIcon.classList.remove('fa-heart');
                        heartIcon.classList.add('fa-heart-o');
                    }
                }
            }
        });
        
        // Listen for message deletions
        channel.bind('App\\Events\\ForumMessageDeleted', function(data) {
            const messageElement = document.querySelector(`.message[data-message-id="${data.messageId}"]`);
            if (messageElement) {
                messageElement.remove();
            }
        });
    });
</script>


</html>