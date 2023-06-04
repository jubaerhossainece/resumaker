@extends('admin.layouts.auth')
@section('page-title', 'Verify 2fa')
@section('form-title','Verify code')
@section('content')
<form method="POST" action="{{ route('verifyCode') }}">
    @csrf
    <div class="row mb-3">
        <label for="2fa" class="col-md-4 col-form-label text-md-end">{{ __('2FA Code') }}</label>
        <div class="col-md-6">
            <input id="2fa" type="number" class="form-control  @if(session('error')) is-invalid @endif" name="code" value="{{ old('code') }}" required autocomplete="2fa" autofocus>
            @if(session('error'))
                <span class="invalid-feedback" role="alert">
                <strong>{{session('error')}}</strong>
            </span>
            @endif
        </div>
    </div>
    <div class="row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-info">
                {{ __('Verify') }}
            </button>
        </div>
    </div>
</form>
@endsection