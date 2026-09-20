@extends('layouts.app')
@section('title','Log In')
@section('content')
<div class="row"><div class="col s12 m6 offset-m3"><div class="card"><div class="card-image"><img src="{{ asset('images/login_header.jpg') }}"></div><div class="card-content"><span class="card-title">Log In</span><p class="muted">Welcome to TalesRunner.</p><form method="post" action="{{ route('login.submit') }}">@csrf<div class="input-field"><input id="account" name="account" value="{{ old('account') }}" required maxlength="64"><label for="account">Username</label></div><div class="input-field"><input id="password" name="password" type="password" required maxlength="64"><label for="password">Password</label></div><button class="btn waves-effect" type="submit">Log In</button>@if(config('talesrunner.features.register')) <a class="btn-flat" href="{{ route('register') }}">Register</a>@endif</form></div></div></div></div>
@endsection
