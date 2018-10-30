<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Blog 2018') - Blog 2018</title>
    <link rel="stylesheet" href="/css/app.css">
    {!! editor_css() !!}
</head>
<body>
@include("layouts._header")

<div class="container">
    <div class="col-md-offset-1 col-md-10">
        @include('shared._messages')
        @yield('content')

        @include("layouts._footer")
    </div>
</div>
<script src="/js/app.js"></script>

{!! editor_js() !!}
{!! editor_config('mdeditor') !!}
</body>
</html>