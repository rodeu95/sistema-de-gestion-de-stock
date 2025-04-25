@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5>{{ __('Restablecer contraseña') }}</h5></div>

                <div class="card-body">
                    <section class="bg-light p-4 rounded shadow">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>

                                <div class="col-md-6">
                                    <div class="input-container">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password"><i class="fa-solid fa-eye icon" id="togglePassword"></i>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirmar Contraseña') }}</label>

                                <div class="col-md-6">
                                    <div class="input-container">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password"><i class="fa-solid fa-eye icon" id="togglePasswordResetConfirm"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn">
                                        {{ __('Restablecer contraseña') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </section>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="{{ asset('js/resetPassToggle.js') }}"></script>
@endpush
@endsection
