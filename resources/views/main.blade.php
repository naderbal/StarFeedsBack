<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials._head')
</head>
<body id="body">

@include('partials._nav')

<div class="container">

    @yield('content')



</div>

@include('partials._javascript')

</body>

<footer>
    @include('partials._footer')
</footer>
</html>