@extends('admin.layouts.auth')
@section('page-title', 'Sign In to Resumake')
@section('form-title','Sign In to Resumake')
@section('content')
{{--    {{ route('password.email') }}--}}
    <form action="#" method="post" class="p-3">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group mb-4">
            <div class="input-group">
                <input type="email" name="email" class="form-control" placeholder="Enter Email" required="">
                <span class="input-group-append"> <button type="submit" class="btn btn-primary waves-effect waves-light">Reset</button> </span>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-sm-7">
                <a href="{{ route('login') }}"><i class="fa mr-1"></i> Log in</a>
            </div>
            {{--                                <div class="col-sm-5 text-right">--}}
            {{--                                    <a href="pages-register.html">Create an account</a>--}}
            {{--                                </div>--}}
        </div>

    </form>
@endsection
