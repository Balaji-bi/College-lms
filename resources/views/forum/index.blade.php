<!-- resources/views/forum/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forum - College LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <style>
        .context-menu {
            position: absolute;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            display: none;
        }
        .context-menu-item {
            padding: 10px 20px;
            cursor: pointer;
            transition: background 0.2s;
            text-align: center;
            color: #333;
            font-weight: 500;
        }
        .context-menu-item:hover {
            background-color: #e9e9e9;
        }
        .message-container {
            height: calc(100vh - 180px);
            overflow-y: auto;
            scroll-behavior: smooth;
        }
        .sticky-date {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .message {
            position: relative;
            transition: background-color 0.2s;
        }
        .message:hover {
            background-color: #f9f9f9;
        }
        .message-input {
            resize: none;
            min-height: 48px;
            max-height: 150px;
        }
        .liked {
            color: #e53e3e;
        }
        .reply-reference {
            border-left: 3px solid #4a5568;
            padding-left: 10px;
            margin-bottom: 8px;
            background-color: #f7fafc;
            font-size: 0.9rem;
            color: #4a5568;
        }
        .image-preview {
            position: relative;
            display: inline-block;
        }
        .remove-preview {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e53e3e;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow">
            <div class="container mx-auto px-4 py-2 flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-gray-500 font-medium">HOME</a>
                <div class="flex items-center">
                    <img src="{{ Auth::user()->avatar ?? '/images/default-avatar.png' }}" alt="User Avatar" class="w-10 h-10 rounded-full">
                </div>
            </div>
        </header>

        <main class="flex-grow container mx-auto px-4 py-6">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div id="message-container" class="message-container p-4">
                    <!-- Messages will be loaded here -->
                    @foreach($messages as $date => $messagesGroup)
                        <div class="sticky-date bg-gray-200 py-2 px-4 rounded-md mb-4 text-center text-gray-600 font-medium">
                            {{ $date }}
                        </div>
                        @foreach($messagesGroup as $message)
                            <div class="message flex mb-4 {{ Auth::id() == $message->sender_id ? 'justify-end' : 'justify-start' }}" 
                                data-id="{{ $message->id }}" 
                                data-sender="{{ $message->sender_id }}">
                                
                                @if(Auth::id() != $message->sender_id)
                                    <div class="flex-shrink-0 mr-3">
                                        <img src="{{ $message->user->avatar ?? '/images/default-avatar.png' }}" alt="Avatar" class="w-8 h-8 rounded-full">
                                    </div>
                                @endif
                                
                                <div class="{{ Auth::id() == $message->sender_id ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-800' }} p-3 rounded-lg max-w-md">
                                    @if(Auth::id() != $message->sender_id)
                                        <div class="text-xs text-gray-500 mb-1">{{ $message->user->name }}</div>
                                    @endif
                                    
                                    @if($message->reply_to_id)
                                        <div class="reply-reference">
                                            <div class="text-xs">Replying to {{ $message->replyTo->user->name ?? 'Unknown' }}</div>
                                            <div class="truncate">{{ Str::limit($message->replyTo->message, 50) }}</div>
                                        </div>
                                    @endif
                                    
                                    <div class="message-content">{{ $message->message }}</div>
                                    
                                    @if($message->image_path)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $message->image_path) }}" alt="Message Image" class="max-w-full rounded-md">
                                        </div>
                                    @endif
                                    
                                    <div class="text-xs text-gray-500 mt-1 flex items-center justify-between">
                                        <span>{{ $message->created_at->format('H:i') }}</span>
                                        @if(count(json_decode($message->liked_by, true) ?? []) > 0)
                                            <span class="liked flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-14a1 1 0 110-2 1 1 0 010 2zm0 12a1 1 0 110-2 1 1 0 010 2zm0-8a1 1 0 00-1 1v4a1 1 0 102 0V9a1 1 0 00-1-1z" clipRule="evenodd" fillRule="evenodd"></path>
                                                </svg>
                                                {{ count(json_decode($message->liked_by, true) ?? []) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(Auth::id() == $message->sender_id)
                                    <div class="flex-shrink-0 ml-3">
                                        <img src="{{ Auth::user()->avatar ?? '/images/default-avatar.png' }}" alt="Your Avatar" class="w-8 h-8 rounded-full">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <div class="border-t border-gray-200 p-4">
                    <form id="message-form" class="flex flex-col">
                        <div id="reply-container" class="bg-gray-100 p-2 rounded mb-2 hidden">
                            <div class="flex justify-between items-center">
                                <span id="reply-text" class="text-sm text-gray-600">Replying to: <span id="reply-content"></span></span>
                                <button type="button" id="cancel-reply" class="text-xs text-red-500">Cancel</button>
                            </div>
                        </div>
                        
                        <div id="image-preview-container" class="mb-2 hidden">
                            <div class="image-preview inline-block">
                                <img id="image-preview" class="max-h-32 rounded" alt="Preview">
                                <span class="remove-preview" id="remove-image">&times;</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <button type="button" id="upload-trigger" class="p-2 rounded-full hover:bg-gray-200 mr-2">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <input type="file" id="image-upload" class="hidden" accept="image/jpeg,image/png">
                            
                            <textarea id="message-input" class="message-input flex-grow border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Message here"></textarea>
                            
                            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-r-lg hover:bg-blue-600 transition duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        
        <!-- Context Menu for Own Messages -->
        <div id="own-context-menu" class="context-menu">
            <div class="context-menu-item" data-action="reply">REPLY</div>
            <div class="context-menu-item" data-action="copy">COPY</div>
            <div class="context-menu-item" data-action="delete">DELETE</div>
        </div>
        
        <!-- Context Menu for Others' Messages -->
        <div id="others-context-menu" class="context-menu">
            <div class="context-menu-item" data-action="reply">REPLY</div>
            <div class="context-menu-item" data-action="copy">COPY</div>
            <div class="context-menu-item" data-action="like">LIKE</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            const messageContainer = document.getElementById('message-container');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const uploadTrigger = document.getElementById('upload-trigger');
            const imageUpload = document.getElementById('image-upload');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const removeImage = document.getElementById('remove-image');
            const replyContainer = document.getElementById('reply-container');
            const replyContent = document.getElementById('reply-content');
            const cancelReply = document.getElementById('cancel-reply');
            const ownContextMenu = document.getElementById('own-context-menu');
            const othersContextMenu = document.getElementById('others-context-menu');
            
            let selectedFile = null;
            let replyToId = null;
            let currentContextMessage = null;
            
            // Scroll to bottom on load
            scrollToBottom();
            
            // Initialize Echo for real-time messaging
            window.Echo.private('forum')
                .listen('NewMessage', (e) => {
                    appendMessage(e.message);
                })
                .listen('MessageLiked', (e) => {
                    updateLikes(e.messageId, e.likedBy);
                })
                .listen('MessageDeleted', (e) => {
                    removeMessage(e.messageId);
                });
                
            // Form submission
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = messageInput.value.trim();
                if (!message && !selectedFile) return;
                
                const formData = new FormData();
                formData.append('message', message);
                if (selectedFile) {
                    formData.append('image', selectedFile);
                }
                if (replyToId) {
                    formData.append('reply_to_id', replyToId);
                }
                
                fetch('{{ route("forum.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        resetImageUpload();
                        resetReply();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
            
            // Image upload handling
            uploadTrigger.addEventListener('click', function() {
                imageUpload.click();
            });
            
            imageUpload.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    // Validate file type and size
                    if (!['image/jpeg', 'image/png'].includes(file.type)) {
                        alert('Only JPG and PNG images are allowed.');
                        resetImageUpload();
                        return;
                    }
                    
                    if (file.size > 5 * 1024 * 1024) { // 5MB
                        alert('Image size should not exceed 5MB.');
                        resetImageUpload();
                        return;
                    }
                    
                    selectedFile = file;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            removeImage.addEventListener('click', resetImageUpload);
            
            // Reply handling
            cancelReply.addEventListener('click', resetReply);
            
            // Context menu setup
            document.addEventListener('click', hideContextMenus);
            
            document.addEventListener('contextmenu', function(e) {
                const messageElement = e.target.closest('.message');
                if (messageElement) {
                    e.preventDefault();
                    
                    hideContextMenus();
                    currentContextMessage = messageElement;
                    
                    const senderId = messageElement.getAttribute('data-sender');
                    const currentUserId = '{{ Auth::id() }}';
                    
                    const contextMenu = senderId === currentUserId ? ownContextMenu : othersContextMenu;
                    
                    contextMenu.style.left = `${e.pageX}px`;
                    contextMenu.style.top = `${e.pageY}px`;
                    contextMenu.style.display = 'block';
                }
            });
            
            // Context menu actions
            const contextMenuItems = document.querySelectorAll('.context-menu-item');
            contextMenuItems.forEach(item => {
                item.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    const messageId = currentContextMessage.getAttribute('data-id');
                    const messageContent = currentContextMessage.querySelector('.message-content').textContent;
                    
                    switch(action) {
                        case 'reply':
                            handleReply(messageId, messageContent);
                            break;
                        case 'copy':
                            copyToClipboard(messageContent);
                            break;
                        case 'like':
                            likeMessage(messageId);
                            break;
                        case 'delete':
                            deleteMessage(messageId);
                            break;
                    }
                    
                    hideContextMenus();
                });
            });
            
            // Auto-resize textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Helper functions
            function appendMessage(message) {
                // Create date group if needed
                const messageDate = new Date(message.created_at).toLocaleDateString();
                let dateGroup = document.querySelector(`[data-date="${messageDate}"]`);
                
                if (!dateGroup) {
                    dateGroup = document.createElement('div');
                    dateGroup.setAttribute('data-date', messageDate);
                    dateGroup.innerHTML = `
                        <div class="sticky-date bg-gray-200 py-2 px-4 rounded-md mb-4 text-center text-gray-600 font-medium">
                            ${messageDate}
                        </div>
                    `;
                    messageContainer.appendChild(dateGroup);
                }
                
                // Create message element
                const messageElement = document.createElement('div');
                messageElement.className = `message flex mb-4 ${message.sender_id == {{ Auth::id() }} ? 'justify-end' : 'justify-start'}`;
                messageElement.setAttribute('data-id', message.id);
                messageElement.setAttribute('data-sender', message.sender_id);
                
                let replyMarkup = '';
                if (message.reply_to) {
                    replyMarkup = `
                        <div class="reply-reference">
                            <div class="text-xs">Replying to ${message.reply_to.user.name}</div>
                            <div class="truncate">${message.reply_to.message.substring(0, 50)}</div>
                        </div>
                    `;
                }
                
                let imageMarkup = '';
                if (message.image_path) {
                    imageMarkup = `
                        <div class="mt-2">
                            <img src="${message.image_path}" alt="Message Image" class="max-w-full rounded-md">
                        </div>
                    `;
                }
                
                const isCurrentUser = message.sender_id == {{ Auth::id() }};
                const avatarUrl = isCurrentUser ? 
                    '{{ Auth::user()->avatar ?? "/images/default-avatar.png" }}' : 
                    message.user.avatar || '/images/default-avatar.png';
                
                messageElement.innerHTML = `
                    ${!isCurrentUser ? `
                        <div class="flex-shrink-0 mr-3">
                            <img src="${avatarUrl}" alt="Avatar" class="w-8 h-8 rounded-full">
                        </div>
                    ` : ''}
                    
                    <div class="${isCurrentUser ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-800'} p-3 rounded-lg max-w-md">
                        ${!isCurrentUser ? `<div class="text-xs text-gray-500 mb-1">${message.user.name}</div>` : ''}
                        ${replyMarkup}
                        <div class="message-content">${message.message}</div>
                        ${imageMarkup}
                        <div class="text-xs text-gray-500 mt-1 flex items-center justify-between">
                            <span>${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                            ${message.liked_by && message.liked_by.length > 0 ? `
                                <span class="liked flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-14a1 1 0 110-2 1 1 0 010 2zm0 12a1 1 0 110-2 1 1 0 010 2zm0-8a1 1 0 00-1 1v4a1 1 0 102 0V9a1 1 0 00-1-1z" clipRule="evenodd" fillRule="evenodd"></path>
                                    </svg>
                                    ${message.liked_by.length}
                                </span>
                            ` : ''}
                        </div>
                    </div>
                    
                    ${isCurrentUser ? `
                        <div class="flex-shrink-0 ml-3">
                            <img src="${avatarUrl}" alt="Your Avatar" class="w-8 h-8 rounded-full">
                        </div>
                    ` : ''}
                `;
                
                messageContainer.appendChild(messageElement);
                scrollToBottom();
            }
            
            function updateLikes(messageId, likedBy) {
                const messageElement = document.querySelector(`.message[data-id="${messageId}"]`);
                if (!messageElement) return;
                
                const messageFooter = messageElement.querySelector('.text-gray-500');
                let likeCounter = messageElement.querySelector('.liked');
                
                if (likedBy.length > 0) {
                    if (likeCounter) {
                        likeCounter.innerHTML = `
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-14a1 1 0 110-2 1 1 0 010 2zm0 12a1 1 0 110-2 1 1 0 010 2zm0-8a1 1 0 00-1 1v4a1 1 0 102 0V9a1 1 0 00-1-1z" clipRule="evenodd" fillRule="evenodd"></path>
                            </svg>
                            ${likedBy.length}
                        `;
                    } else {
                        likeCounter = document.createElement('span');
                        likeCounter.className = 'liked flex items-center';
                        likeCounter.innerHTML = `
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-14a1 1 0 110-2 1 1 0 010 2zm0 12a1 1 0 110-2 1 1 0 010 2zm0-8a1 1 0 00-1 1v4a1 1 0 102 0V9a1 1 0 00-1-1z" clipRule="evenodd" fillRule="evenodd"></path>
                            </svg>
                            ${likedBy.length}
                        `;
                        messageFooter.appendChild(likeCounter);
                    }
                } else if (likeCounter) {
                    likeCounter.remove();
                }
            }
            
            function removeMessage(messageId) {
                const messageElement = document.querySelector(`.message[data-id="${messageId}"]`);
                if (messageElement) {
                    messageElement.remove();
                }
            }
            
            function handleReply(messageId, content) {
                replyToId = messageId;
                replyContent.textContent = content.substring(0, 30) + (content.length > 30 ? '...' : '');
                replyContainer.classList.remove('hidden');
                messageInput.focus();
            }
            
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        // Optional: Show a toast notification
                        // showToast('Message copied to clipboard');
                    })
                    .catch(err => console.error('Failed to copy: ', err));
            }
            
            function likeMessage(messageId) {
                fetch(`{{ url('forum/message') }}/${messageId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            
            function deleteMessage(messageId) {
                if (confirm('Are you sure you want to delete this message?')) {
                    fetch(`{{ url('forum/message') }}/${messageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
            
            function resetImageUpload() {
                selectedFile = null;
                imageUpload.value = '';
                imagePreviewContainer.classList.add('hidden');
            }
            
            function resetReply() {
                replyToId = null;
                replyContainer.classList.add('hidden');
            }
            
            function hideContextMenus() {
                ownContextMenu.style.display = 'none';
                othersContextMenu.style.display = 'none';
            }
            
            function scrollToBottom() {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        });
    </script>
</body>
</html>