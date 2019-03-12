@extends('auth.app')

@section('content')

    <form id="sign_in" role="form" method="POST" action="/login">
        {{ csrf_field() }}
        <div class="msg">Sign in to start your session</div>

        @if(session('notification'))
            <div class="alert alert-danger text-center">
                {{session('notification')}}
            </div>
        @endif

        <div class="input-group">
        <span class="input-group-addon">
        <i class="material-icons">person</i>
        </span>
            <div class="form-line">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required autofocus>


            </div>
            @if ($errors->has('email'))
                <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
            {{--@if ($errors->has('email'))--}}
            {{--<label id="name-error" class="error" for="email">{{ $errors->first('email') }}</label>--}}
            {{--@endif--}}
        </div>
        <div class="input-group">
        <span class="input-group-addon">
        <i class="material-icons">lock</i>
        </span>
            <div class="form-line">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            {{--@if ($errors->has('password'))--}}
            {{--<label id="name-error" class="error" for="name">{{ $errors->first('password') }}</label>--}}
            {{--@endif--}}
        </div>
        <div class="row">
            <div class="col-xs-8 p-t-5">
                <input type="checkbox" name="remember"
                       {{ old('remember') ? 'checked' : '' }} class="filled-in chk-col-pink" id="rememberme">
                <label for="rememberme">Remember Me</label>
            </div>
            <div class="col-xs-4">
                <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
            </div>
        </div>
    </form>
@endsection
