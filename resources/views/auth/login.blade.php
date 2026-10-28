@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-5">
            <div class="card p-3 shadow-lg border-0">
                <div class="card-header bg-white border-0 text-center pt-4 pb-2">
                    <h3 class="fw-bold" style="color: #4f46e5;">Iniciar Sesión</h3>
                    <p class="text-muted small">Ingresa tus credenciales para acceder</p>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">Correo Electrónico</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="ejemplo@correo.com">
                            
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">Contraseña</label>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember">
                                    Recordarme
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none small" href="{{ route('password.request') }}" style="color: #4f46e5;">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                Ingresar
                            </button>
                        </div>
                    </form>

                    <!-- Logo inferior de adorno -->
<div class="text-center mt-5 mb-2">
    <img src="{{ asset('images/condominio_reverse.png') }}" 
         alt="Logo CondoGest" 
         class="img-fluid" 
         style="max-height: 70px; opacity: 0.6; filter: grayscale(100%); transition: all 0.3s ease;"
         onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'"
         onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%)'">
</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection