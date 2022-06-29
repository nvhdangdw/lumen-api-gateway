<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title', 'Lifestyle Community')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/store.js') }}" defer></script>
    
    <script src="{{ asset('js/lib/dataTables.editor.min.js') }}" defer></script>
    <script src="{{ asset('js/lib/dataTables.ellipsis.js') }}" defer></script>
    <script src="{{ asset('js/lib/dataTables.min.js') }}" defer></script>
    
    <script src="{{ asset('js/lib/iziModal.min.js') }}" defer></script>
    <script src="{{ asset('js/lib/iziToast.min.js') }}" defer></script>
    
    <script src="{{ asset('js/lib/jquery.hotkeys.js') }}" defer></script>
    <script src="{{ asset('js/lib/jquery.serializejson.min.js') }}" defer></script>
    <script src="{{ asset('js/lib/jquery.validate.min.js') }}" defer></script>
    
    <script src="{{ asset('js/lib/lodash.min.js') }}" defer></script>
    <script src="{{ asset('js/lib/moment.js') }}" defer></script>
    
    <script src="{{ asset('js/lib/URI.min.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/store.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/lib/bootstrap-float-label.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/editor.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/iziModal.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/iziToast.min.css') }}" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.4.1.js" type="text/javascript"></script>
</head>
<body>
    <input type="hidden" id="supplier_id" value="{{ $supplier->supplier_id ?? 0 }}" >
    <div id="app">

        <main class="">
            @yield('content')
        </main>
    </div>
    <div id="overlay" style="display:none;">
        <div class="spinner"></div>
        <br/>
        Loading...
    </div>
</body>
</html>
