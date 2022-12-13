@extends('layouts.app')
<link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="css/login.css">
@section('content-login')
<div class="container">
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
          <div class="card login-card">
            <div class="row no-gutters">
              <div class="col-md-5">                  
                <img src="img/login.jpg" alt="login" class="login-card-img">
              </div>
              <div class="col-md-7">
                <div class="card-body">
                  <div class="brand-wrapper">
                    <img src="img/logo.png" alt="logo" class="logo">
                  </div>
                  <br/>
                  <p class="login-card-description">Iniciar sesión</p>
                  <form class="form-login" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <label for="username" class="sr-only">Usuario</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Usuario" value="{{ old('username') }}" required autofocus>
                        @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="sr-only">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <input  type="submit" name="login" id="login" class="btn btn-block login-btn mb-4" type="button" value="Login">
                </form>
                </div>
              </div>
            </div>
          </div>
         
        </div>
      </main>
</div>

@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
@endsection