@extends('layouts.headless')

@section('content')
<link href="{{ asset('dist/css/login.css') }}" rel="stylesheet">
<div class="container">
    <div class="row justify-content-center">
    <form class="form-signin" method="POST" action="{{ route('store_login') }}">
        @csrf
        <img class="mb-4" src="{{ asset('media/logo.png') }}" alt="" width="300" height="300">
        <h1 class="h3 mb-3 font-weight-normal text-center">Please sign in</h1>
        <label for="email" class="sr-only">{{ __('E-Mail Address') }}</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('E-Mail Address') }}" required autofocus>
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
        <label for="inputPassword" class="sr-only">{{ __('Password') }}</label>
        <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        <div class="checkbox mb-3 text-center">
            <label>
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">{{ __('Login') }}</button>      
    </form>
    </div>
</div>
@endsection
