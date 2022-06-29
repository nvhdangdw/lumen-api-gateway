<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $supplier->name }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/store.js') }}" defer></script>
    <script src="{{ asset('js/common.js') }}" defer></script>
    
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
            <script src="{{ asset('js/qrcode/html5-qrcode.min.js') }}"></script>
            <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
                <a class="navbar-brand col-sm-3 col-md-3 mr-0" href="#">{{$supplier->name ?? ''}} </a>
                <!--<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search" />-->
                <ul class="navbar-nav px-3">
                    <li class="nav-item text-nowrap">
                        <a class="nav-link" href="{{ route('store_logout') }}">Sign out</a>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
                <div class="row">
                    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                        <div class="sidebar-sticky">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('store_dashboard') }}">
                                        <span data-feather="home"></span>
                                        Dashboard <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('store_promotion') }}">
                                        <span data-feather="home"></span>
                                        Promotions <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    @yield('content')                    
                </div>
            </div>
        </main>
    </div>
    <div id="overlay" style="display:none;">
        <div class="spinner"></div>
        <br/>
        Loading...
    </div>
    <div id="modal-info" data-izimodal-title="Info" class="iziModal"></div>
    <div id="modal-warning" data-izimodal-title="Warning:" class="iziModal"></div>
    <div id="modal-error" data-izimodal-title="Error!" class="iziModal"></div>
</body>
</html>
