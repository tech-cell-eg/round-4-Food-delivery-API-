@extends('admin.chat.app')

@push('styles')
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
    <style>
        .chat-container {
            display: flex;
            height: calc(100vh - 150px);
        }
        
        @media (max-width: 768px) {
            .chat-container {
                flex-direction: column;
                height: auto;
            }
            
            #chat-sidebar {
                display: none;
            }
            
            #chat-sidebar.show-on-mobile {
                display: block !important;
            }
            
            #chat-main {
                display: none;
            }
            
            #chat-main.show-on-mobile {
                display: block !important;
            }
        }
        
        .message {
            margin-bottom: 15px;
            max-width: 80%;
            clear: both;
            position: relative;
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes highlightMessage {
            0% { background-color: rgba(13, 110, 253, 0.1); }
            100% { background-color: transparent; }
        }
        
        .new-message {
            animation: highlightMessage 2s ease-out;
        }
    </style>
@endpush

@section('chat_content')
<div class="chat-container">
    <!-- قائمة المحادثات -->
    <div class="chat-sidebar border-end bg-white" id="chat-sidebar" style="width: 350px; min-width: 350px;">
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-white p-0 me-2 d-md-none back-button" id="toggle-chat-list">
                    <i class="fas fa-arrow-right"></i>
                </button>
                <h5 class="mb-0">المحادثات</h5>
            </div>
            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#newChatModal">
                <i class="fas fa-plus"></i> جديد
            </button>
        </div>
        <div class="chat-list">
            @if($conversations->isEmpty())
                <div class="no-conversations">
                    <i class="fas fa-comment-slash"></i>
                    <p>لا توجد محادثات بعد</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal">
                        بدء محادثة جديدة
                    </button>
                </div>
            @else
                @foreach($conversations as $conv)
                    <a href="{{ route('chat.show', $conv->id) }}" class="chat-item {{ $conv->id == $conversation->id ? 'active' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 position-relative">
                                @if($conv->customer_id == auth()->id())
                                    <img src="{{ $conv->chef->user->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                                         alt="{{ $conv->chef->user->name }}" 
                                         class="rounded-circle" 
                            : $conv->customer->user;
                        
                        $unreadCount = $conv->messages()
                            ->where('sender_type', '!=', auth()->user()->type)
                            ->whereNull('read_at')
                            ->count();
                    @endphp
                    
                    <a href="{{ route('chat.show', $conv->id) }}" class="text-decoration-none">
                        <div class="conversation-item {{ $conversation->id === $conv->id ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $otherUser->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) }}" 
                                         alt="{{ $otherUser->name }}" 
                                         class="rounded-circle me-2" 
                                         width="50" 
                                         height="50">
                                    <div>
                                        <h6 class="mb-0">{{ $otherUser->name }}</h6>
                                        <small class="text-muted">
                                            @if($conv->lastMessage)
                                                {{ Str::limit($conv->lastMessage->message, 30) }}
                                            @else
                                                لا توجد رسائل بعد
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @if($unreadCount > 0 && $conversation->id !== $conv->id)
                                    <span class="unread-count">{{ $unreadCount }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        
        <!-- منطقة المحادثة -->
        <div class="flex-grow-1 d-flex flex-column" id="chat-main">
            <div class="chat-header d-flex align-items-center">
                <button class="btn btn-link text-white p-0 me-2 d-md-none back-button" id="back-to-conversations">
                    <i class="fas fa-arrow-right"></i>
                </button>
                <div class="user-info">
                    @if($conversation->customer_id == auth()->id())
                        <img src="{{ $conversation->chef->user->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                             alt="{{ $conversation->chef->user->name }}" 
                             class="user-avatar">
                        <div>
                            <div class="user-name">{{ $conversation->chef->user->name }}</div>
                            <div class="user-status">
                                <span class="status-dot status-online"></span>
                                <span>متصل الآن</span>
                            </div>
                        </div>
                    @else
                        <img src="{{ $conversation->customer->user->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                             alt="{{ $conversation->customer->user->name }}" 
                             class="user-avatar">
                        <div>
                            <div class="user-name">{{ $conversation->customer->user->name }}</div>
                            <div class="user-status">
                                <span class="status-dot status-online"></span>
                                <span>متصل الآن</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="ms-auto">
                    <button class="btn btn-sm btn-light me-2" title="معلومات المحادثة">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-sm btn-light" type="button" id="chatMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chatMenuButton">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>عرض الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-bell-slash me-2"></i>كتم الإشعارات</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash-alt me-2"></i>حذف المحادثة</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="chat-messages flex-grow-1 position-relative" id="chat-messages">
                <!-- مؤشر التحميل -->
                <div id="loading-indicator" class="text-center py-3 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0">جاري تحميل الرسائل...</p>
                </div>
                
                <!-- قائمة الرسائل -->
                <div id="messages-container">
                    @foreach($conversation->messages->sortBy('created_at') as $message)
                        <div class="message {{ $message->sender_type === auth()->user()->type ? 'sent' : 'received' }}" id="message-{{ $message->id }}">
                            @if($message->sender_type !== auth()->user()->type)
                                <div class="message-sender">
                                    {{ $message->sender_type === 'customer' ? $conversation->customer->user->name : $conversation->chef->user->name }}
                                </div>
                            @endif
                            <div class="message-bubble">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                            <small class="message-time">
                                {{ $message->created_at->diffForHumans() }}
                                @if($message->sender_type === auth()->user()->type)
                                    @if($message->read_at)
                                        <i class="fas fa-check-double text-primary ms-1" title="تمت القراءة"></i>
                                    @else
                                        <i class="fas fa-check text-muted ms-1" title="تم الإرسال"></i>
                                    @endif
                                @endif
                            </small>
                        </div>
                    @endforeach
                </div>
                
                <!-- رسالة عدم وجود رسائل -->
                @if($conversation->messages->isEmpty())
                    <div class="text-center py-5 text-muted" id="no-messages">
                        <i class="fas fa-comment-slash fa-3x mb-3"></i>
                        <p class="mb-0">لا توجد رسائل بعد. ابدأ المحادثة الآن!</p>
                    </div>
                @endif
            </div>
            
            <!-- نموذج إرسال الرسالة -->
            <div class="chat-input bg-white p-3 border-top">
                <form id="message-form" class="d-flex align-items-center">
                    <button type="button" class="btn btn-link text-muted me-2" title="إرفاق ملف">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <div class="position-relative flex-grow-1">
                        <input type="text" 
                               id="message-input" 
                               class="form-control" 
                               placeholder="اكتب رسالتك هنا..." 
                               autocomplete="off"
                               style="border-radius: 20px; padding-right: 45px;"
                               required>
                        <button type="button" class="btn btn-link text-muted position-absolute end-0 top-50 translate-middle-y me-2" 
                                style="padding: 0;"
                                title="إضافة تعبير">
                            <i class="far fa-smile"></i>
                        </button>
                    </div>
                    <button type="submit" class="btn btn-primary ms-2 rounded-circle" id="send-button" style="width: 40px; height: 40px;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let lastUpdated = {{ $conversation->messages->isNotEmpty() ? $conversation->messages->last()->updated_at->timestamp : 0 }};
        let isLoadingMore = false;
        
        // تهيئة عناصر DOM
        const chatSidebar = document.getElementById('chat-sidebar');
        const chatMain = document.getElementById('chat-main');
        const toggleChatList = document.getElementById('toggle-chat-list');
        const backToConversations = document.getElementById('back-to-conversations');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const messagesContainer = document.getElementById('messages-container');
        const chatMessages = document.getElementById('chat-messages');
        
        // التحكم في عرض قائمة المحادثات على الأجهزة المحمولة
        function toggleMobileView() {
            if (window.innerWidth < 768) {
                chatSidebar.classList.remove('show-on-mobile');
                chatMain.classList.add('show-on-mobile');
            } else {
                chatSidebar.classList.remove('d-none');
                chatMain.classList.remove('d-none');
            }
        }
        
        // استدعاء الدالة عند تحميل الصفحة وتغيير حجم النافذة
        toggleMobileView();
        window.addEventListener('resize', toggleMobileView);
        
        // النقر على زر عرض/إخفاء قائمة المحادثات
        if (toggleChatList) {
            toggleChatList.addEventListener('click', function() {
                chatSidebar.classList.toggle('show-on-mobile');
                chatMain.classList.toggle('show-on-mobile');
            });
        }
        
        // النقر على زر العودة إلى قائمة المحادثات
        if (backToConversations) {
            backToConversations.addEventListener('click', function() {
                chatSidebar.classList.add('show-on-mobile');
                chatMain.classList.remove('show-on-mobile');
            });
        }
        
        // التمرير للأسفل عند التحميل
        function scrollToBottom(behavior = 'auto') {
            chatMessages.scrollTo({
                top: chatMessages.scrollHeight,
                behavior: behavior
            });
        }
        
        // تشغيل صوت عند وصول رسالة جديدة
        function playMessageSound() {
            const audio = new Audio('{{ asset("sounds/message.mp3") }}');
            audio.play().catch(e => console.log('تعذر تشغيل صوت الإشعار', e));
        }
        
        // إرسال رسالة جديدة
        if (messageForm) {
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = messageInput.value.trim();
                
                if (message === '') return;
                
                // تعطيل زر الإرسال وحقل الإدخال مؤقتًا
                const sendButton = document.getElementById('send-button');
                const originalButtonContent = sendButton.innerHTML;
                sendButton.disabled = true;
                messageInput.disabled = true;
                
                // إضافة مؤشر التحميل
                sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                
                // إضافة الرسالة مباشرة إلى الواجهة (تحسين تجربة المستخدم)
                const tempId = 'temp-' + Date.now();
                const currentUser = {!! auth()->user()->toJson() !!};
                
                const messageHtml = `
                    <div class="message sent" id="${tempId}">
                        <div class="message-bubble">
                            ${message.replace(/\n/g, '<br>')}
                        </div>
                        <small class="message-time">
                            الآن
                            <i class="fas fa-check text-muted ms-1" title="جاري الإرسال"></i>
                        </small>
                    </div>
                `;
                
                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                document.getElementById('no-messages')?.classList.add('d-none');
                scrollToBottom('smooth');
                
                // إرسال الطلب
                fetch('{{ route("chat.send", $conversation->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    // تحديث معرف الرسالة المؤقت بالمعرف الحقيقي
                    const tempElement = document.getElementById(tempId);
                    if (tempElement) {
                        tempElement.id = 'message-' + data.message.id;
            }
        });
        
        // تحميل المزيد من الرسائل عند التمرير لأعلى
        let isLoadingMore = false;
        $('#chat-messages').on('scroll', function() {
            if ($(this).scrollTop() === 0 && !isLoadingMore) {
                loadMoreMessages();
            }
        });
        
        // تحميل المزيد من الرسائل
        function loadMoreMessages() {
            if (isLoadingMore) return;
            
            isLoadingMore = true;
            const firstMessage = $('.message').first();
            const firstMessageId = firstMessage.length ? firstMessage.attr('id').replace('message-', '') : 0;
            
            if (!firstMessageId) {
                isLoadingMore = false;
                return;
            }
            
            $.get("{{ route('chat.messages', $conversation->id) }}", { 
                before_id: firstMessageId,
                limit: 10
            })
            .done(function(response) {
                if (response.messages && response.messages.length > 0) {
                    const scrollPosition = $('#chat-messages').scrollTop();
                    const scrollHeight = $('#chat-messages')[0].scrollHeight;
                    
                    response.messages.reverse().forEach(function(message) {
                        const isSent = message.sender_type === '{{ auth()->user()->type }}';
                        const messageClass = isSent ? 'sent' : 'received';
                        const senderName = isSent ? '{{ auth()->user()->name }}' : 
                            (message.sender_type === 'customer' ? '{{ $conversation->customer->user->name }}' : '{{ $conversation->chef->user->name }}');
                        
                        const messageHtml = `
                            <div class="message ${messageClass}" id="message-${message.id}">
                                ${!isSent ? `<div class="message-sender">${senderName}</div>` : ''}
                                <div class="message-bubble">
                                    ${message.message.replace(/\n/g, '<br>')}
                                </div>
                                <small class="message-time">
                                    ${formatTime(message.created_at)}
                                    ${isSent ? 
                                        (message.read_at ? 
                                            '<i class="fas fa-check-double text-primary ms-1" title="تمت القراءة"></i>' : 
                                            '<i class="fas fa-check text-muted ms-1" title="تم الإرسال"></i>') 
                                        : ''
                                    }
                                </small>
                            </div>
                        `;
                        
                        $('#messages-container').prepend(messageHtml);
                    });
                    
                    // الحفاظ على موضع التمرير
                    const newScrollHeight = $('#chat-messages')[0].scrollHeight;
                    $('#chat-messages').scrollTop(scrollPosition + (newScrollHeight - scrollHeight));
                }
            })
            .fail(function(xhr) {
                console.error('فشل في تحميل المزيد من الرسائل:', xhr);
            })
            .always(function() {
                isLoadingMore = false;
            });
        }
    });
</script>
@endpush
@endsection
