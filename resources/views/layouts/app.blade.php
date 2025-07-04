<!DOCTYPE html>
<html lang="ar" dir="rtl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', config('app.name'))</title>

        <!-- Bootstrap RTL CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @stack('styles')
    </head>

    <body>
        <div class="d-flex">
            <!-- القائمة الجانبية -->
            <div class="bg-dark text-white" style="width: 250px; min-height: 100vh;">
                <div class="p-3">
                    <h4 class="text-center mb-4">لوحة التحكم</h4>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('chat.index') }}" class="nav-link text-white {{ request()->routeIs('chat.*') ? 'bg-primary' : '' }}">
                                <i class="fas fa-comments me-2"></i> المحادثات
                            </a>
                        </li>
                        <!-- يمكنك إضافة المزيد من عناصر القائمة هنا -->
                    </ul>

                    <div class="mt-4 pt-3 border-top">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">
                                <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- المحتوى الرئيسي -->
            <div class="flex-grow-1">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    @include('includes.navbar')
                </nav>


                <main class="p-4">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/update.js') }}"></script>

        @stack('scripts')
    </body>

</html>