<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('app.name'))</title>
<link rel="stylesheet" href="{{ asset('css/materialize.css') }}">
<link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
body{background:#f2f2f2;display:flex;min-height:100vh;flex-direction:column}main{flex:1 0 auto}.container-narrow{max-width:1100px;margin:24px auto}.card{border-radius:8px}.nav-wrapper{padding:0 14px}.brand-logo{font-size:1.6rem!important}.flash{padding:12px 16px;border-radius:6px;margin:16px 0}.flash.ok{background:#e8f5e9;color:#1b5e20}.flash.bad{background:#ffebee;color:#b71c1c}.item-card img{width:64px;height:64px;object-fit:contain}.muted{color:#777}.stat{font-size:1.2rem;font-weight:500}.inline-form{display:inline}.pagination-wrap nav{background:transparent;box-shadow:none}.pagination-wrap a{color:#1565c0}.admin-grid{display:grid;grid-template-columns:280px 1fr;gap:20px}@media(max-width:800px){.admin-grid{grid-template-columns:1fr}.brand-logo{font-size:1.1rem!important}}
</style>
</head>
<body>
<nav><div class="nav-wrapper"><a href="{{ session('tr_user_id') ? route('home') : route('login') }}" class="brand-logo">TalesRunner</a><ul class="right hide-on-med-and-down">
@if(session('tr_user_id'))
<li><a href="{{ route('home') }}"><i class="fa fa-user"></i> {{ session('tr_user_id') }}</a></li>
@if(config('talesrunner.features.shop'))<li><a href="{{ route('shop') }}">Item Shop</a></li>@endif
@if(config('talesrunner.features.topup'))<li><a href="{{ route('topup') }}">Top Up</a></li>@endif
@if(config('talesrunner.features.vip'))<li><a href="{{ route('vip') }}">VIP</a></li>@endif
<li><a href="{{ route('game.alchemist') }}">Game Information</a></li>
@if(config('talesrunner.features.download'))<li><a href="{{ route('download') }}">Download</a></li>@endif
<li><form class="inline-form" method="post" action="{{ route('logout') }}">@csrf<button class="btn-flat white-text" type="submit">Log Out</button></form></li>
@else
<li><a href="{{ route('login') }}">Home</a></li>
@if(config('talesrunner.features.download'))<li><a href="{{ route('download') }}">Download</a></li>@endif
@if(config('talesrunner.features.register'))<li><a href="{{ route('register') }}">Register</a></li>@endif
@endif
</ul></div></nav>
<main><div class="container container-narrow">
@if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
@if(session('error'))<div class="flash bad">{{ session('error') }}</div>@endif
@if($errors->any())<div class="flash bad"><strong>Please fix the following:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')
</div></main>
<footer class="page-footer"><div class="container"><div class="row"><div class="col s12 m8"><h5>Contact Us</h5><p>Official TalesRunner private-server portal.</p></div><div class="col s12 m4"><a class="white-text" href="{{ config('talesrunner.facebook_url') }}" target="_blank">Facebook</a></div></div></div><div class="footer-copyright"><div class="container">TalesRunner Web Portal · Laravel Edition</div></div></footer>
<script src="{{ asset('js/jquery.js') }}"></script><script src="{{ asset('js/materialize.js') }}"></script><script>$(function(){$('.modal').modal();$('.collapsible').collapsible();$('select').material_select&&$('select').material_select();});</script>
@stack('scripts')
</body></html>
