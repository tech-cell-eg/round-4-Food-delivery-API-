<!doctype html>
<html lang="en">

@include('dashboard.partials._head')

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
