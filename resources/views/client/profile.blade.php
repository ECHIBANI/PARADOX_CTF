@extends('layouts.app')

@section('title', 'Mon Compte — PARDOX')

@section('body')
@include('layouts.navbar')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="mb-4 fw-bold">Mon Compte</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('client.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">Nom complet</label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-medium">Numéro de téléphone</label>
                            <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="+212600000000">
                            <div class="form-text">Format: +212XXXXXXXXX</div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3 fw-bold">Modifier le mot de passe</h5>
                        <p class="text-muted small mb-4">Laissez les champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">Nouveau mot de passe</label>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="password_confirmation" class="form-label fw-medium">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn-pardo-primary btn-lg justify-content-center">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
@endsection
