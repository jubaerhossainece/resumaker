
@extends('admin.layouts.auth')
@section('page-title', 'Verify 2fa')
@section('form-title','Verify code')
@section('content')
<div class="card-body">
    @if(\Illuminate\Support\Facades\Auth::user()->google2fa_enabled)
        <p class="card-text">Use this secret key <span class="text-info">{{$secret}}</span> or scan this QR code with your authenticator app to connect phone</p>
        <div>
            {!! $QR_Image !!}
        </div>
        <a href="{{route('google2faStatusChange',['status' => 'disable'])}}" class="btn btn-danger btn-sm">Disable 2FA</a>
    @else
        <a href="{{route('google2faStatusChange',['status' => 'enable'])}}" class="btn btn-success btn-sm">Enable 2FA</a>
    @endif
    <br>
</div>
@endsection
