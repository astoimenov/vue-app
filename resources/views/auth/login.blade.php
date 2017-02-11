@extends('layouts.app')

@section('content')
    <h2 class="title">Login</h2>
    <div class="columns">
        <form class="column is-half" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}

            <label for="email" class="label">E-Mail Address</label>

            <div class="control">
                <input id="email" type="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                    name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="help is-danger">
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>

            <label for="password" class="label">Password</label>

            <div class="control">
                <input id="password" type="password" class="input{{ $errors->has('password') ? ' is-danger' : '' }}"
                    name="password" required>

                @if ($errors->has('password'))
                    <span class="help is-danger">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </div>

            <div class="control">
                <label class="checkbox">
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>

            <div class="control is-grouped">
                <div class="control">
                    <button type="submit" class="button is-primary">
                        Login
                    </button>
                </div>

                <div class="control">
                    <a class="button is-link" href="{{ url('/password/reset') }}">
                        Forgot Your Password?
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
