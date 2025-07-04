<div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
            <!-- روابط التنقل الرئيسية -->
        </ul>

        <ul class="navbar-nav">
            <!-- زر المستخدم -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-1"></i>
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="/profile">
                            <i class="fas fa-user me-2"></i>الملف الشخصي
                        </a></li>
                    <li><a class="dropdown-item" href="{{ url('/settings') }}">
                            <i class="fas fa-cog me-2"></i>الإعدادات
                        </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                            </button>
                        </form>
                    </li>
                </ul>
            </li>



            <!-- زر عرض الوجبات -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dishes.index') }}">
                    <i class="fas fa-utensils"></i>
                    <span class="d-none d-md-inline">الوجبات</span>
                </a>
            </li>

            <!-- زر السلة -->
            <li class="nav-item position-relative">
                <a class="nav-link" href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    {{-- <span class="d-none d-md-inline">السلة</span> --}}
                    @php $cart=[] @endphp

                    <span id="cart-count" class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger">
                        {{ count($cart) > 9 ? '9+' :  0 }}
                    </span>

                </a>
            </li>

            <!-- زر المحادثات -->
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-comments"></i>
                    @if(Auth::user()->unread_messages_count > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ Auth::user()->unread_messages_count > 9 ? '9+' : Auth::user()->unread_messages_count }}
                    </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-messages" aria-labelledby="messagesDropdown">
                    <h6 class="dropdown-header">المحادثات الحديثة</h6>
                    @foreach(Auth::user()->unread_conversations as $conversation)
                    <a class="dropdown-item" href="{{ route('chat.show', $conversation) }}">
                        <div class="d-flex align-items-center">
                            <img src="{{ optional($conversation->otherUser)->avatar_url ?? asset('images/default-avatar.png') }}" class="rounded-circle me-2" width="30"
                                height="30">
                            <div class="text-truncate" style="max-width: 200px;">
                                <strong>{{ optional($conversation->otherUser)->name ?? __('مستخدم غير معروف') }}</strong>
                                <div class="small text-muted text-truncate">
                                    {{ $conversation->last_message->message ?? 'لا توجد رسائل' }}
                                </div>
                            </div>
                            @if($conversation->unread_count > 0)
                            <span class="badge bg-primary rounded-pill ms-2">
                                {{ $conversation->unread_count }}
                            </span>
                            @endif
                        </div>
                    </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="{{ route('chat.index') }}">
                        عرض جميع المحادثات
                    </a>
                </div>
            </li>

            <!-- زر الإشعارات -->
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    @if(Auth::user()->unread_notifications_count > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ Auth::user()->unread_notifications_count > 9 ? '9+' : Auth::user()->unread_notifications_count }}
                    </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-notifications" aria-labelledby="notificationsDropdown">
                    <h6 class="dropdown-header">الإشعارات</h6>
                    @foreach(Auth::user()->unreadNotifications as $notification)
                    <a class="dropdown-item" href="{{ $notification->data['url'] ?? '#' }}">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }} text-{{ $notification->data['type'] ?? 'primary' }}"></i>
                            </div>
                            <div class="text-truncate" style="max-width: 250px;">
                                <div>{{ $notification->data['title'] ?? 'إشعار جديد' }}</div>
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @if($notification->unread())
                            <span class="badge bg-primary rounded-pill ms-2">جديد</span>
                            @endif
                        </div>
                    </a>
                    @if(!$loop->last)
                    <div class="dropdown-divider"></div>
                    @endif
                    @endforeach
                    @if(Auth::user()->unreadNotifications->isEmpty())
                    <div class="dropdown-item text-center text-muted py-3">
                        {{ __('No notifications yet') }}
                    </div>
                    @else
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                        {{ __('View all notifications') }}
                    </a>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</div>