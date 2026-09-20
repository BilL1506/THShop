@extends('layouts.app')
@section('title','Download')
@section('content')<h4>Download</h4><div class="row">@forelse($files as $file)<div class="col s12 m6"><div class="card"><div class="card-content"><span class="card-title">{{ $file->FileTitle }}</span><p>{{ $file->FileDescription }}</p></div><div class="card-action"><a href="{{ $file->FileURL }}" target="_blank">Download</a></div></div></div>@empty<div class="card-panel">No downloads are configured.</div>@endforelse</div>@endsection
