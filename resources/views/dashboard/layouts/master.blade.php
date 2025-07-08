<!doctype html>
<html lang="en">

<head>
    @include('dashboard.partials._head')

    <title>@yield("title")</title>
</head>



<body>
<!--wrapper-->
<div class="wrapper">
    @include('dashboard.partials._sidebar')


    @include('dashboard.partials._navbar')

    @yield("content")

    @include('dashboard.partials._footer')
</div>
<!--end wrapper-->

@include('dashboard.partials._scripts')
</body>

</html>
