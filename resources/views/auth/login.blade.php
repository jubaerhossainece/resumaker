@extends('admin.layouts.auth')
@section('page-title', 'Sign In page')
@section('form-title','Sign In to Cv Maker Dashboard')
@section('content')

    @if(Session::has('message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{Session::get('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    @endif

    <form action="{{ route('login') }}" method="post" class="p-3">
        @csrf
        <div class="form-group mb-3">
            <input class="form-control" name="email" type="email" value="{{old('email')}}" required="" placeholder="Email">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <input class="form-control" name="password" type="password" value="{{old('password')}}" required="" placeholder="Password">
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @endif
        </div>

        <div class="form-group mb-3">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="remember" class="custom-control-input" id="checkbox-signin">
                <label class="custom-control-label" for="checkbox-signin">Remember me</label>
            </div>
        </div>

        <div class="form-group text-center mt-5 mb-4">
            <button class="btn btn-primary waves-effect width-md waves-light" type="submit"> Log
                In
            </button>
        </div>

        <div class="form-group row mb-0">
            <div class="col-sm-7">
                <a href="{{ route('password.request') }}"><i class="fa fa-lock mr-1"></i> Forgot your password?</a>
            </div>
            {{--                                <div class="col-sm-5 text-right">--}}
            {{--                                    <a href="pages-register.html">Create an account</a>--}}
            {{--                                </div>--}}
        </div>
    </form>
@endsection
