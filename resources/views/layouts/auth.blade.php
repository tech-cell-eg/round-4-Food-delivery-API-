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

        <style>
            body {
                background-color: #f8f9fa;
                height: 100vh;
                display: flex;
                align-items: center;
            }

            .auth-card {
                border: none;
                border-radius: 10px;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                overflow: hidden;
            }

            .auth-header {
                background-color: #4e73df;
                color: white;
                padding: 1.5rem;
                text-align: center;
            }

            .auth-body {
                padding: 2rem;
                background-color: white;
            }

            .form-control:focus {
                border-color: #4e73df;
                box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            }

            .btn-primary {
                background-color: #4e73df;
                border-color: #4e73df;
            }

            .btn-primary:hover {
                background-color: #2e59d9;
                border-color: #2653d4;
            }

            .auth-footer {
                text-align: center;
                padding: 1rem;
                background-color: #f8f9fa;
                border-top: 1px solid #e3e6f0;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="auth-card">
                        <div class="auth-header">
                            <h4><i class="fas fa-user-shield me-2"></i> @yield('auth-title')</h4>
                        </div>

                        <div class="auth-body">
                            @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
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
                        </div>

                        <div class="auth-footer">
                            @yield('auth-footer')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        @stack('scripts')
    </body>

</html>