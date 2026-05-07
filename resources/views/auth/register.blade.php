@extends('layouts.app')
@section('title','Inscription — PARDOX')

@section('body')
<div class="auth-wrapper d-flex align-items-center justify-content-center p-3">
  <div class="auth-card">
    <div class="text-center mb-4">
      <img src="{{ asset('images/logo.png') }}" style="width: 150px; margin-bottom: 1.5rem;" alt="PARDOX">
      <h1 class="auth-title mb-1">Créer un compte</h1>
      <p class="text-muted" style="font-size:.9rem;">Inscription gratuite — sans email requis</p>
    </div>

    <div class="tab-custom mb-4">
      <a href="{{ route('login') }}" class="tab-btn text-decoration-none text-center d-flex align-items-center justify-content-center">Connexion</a>
      <button class="tab-btn active">S'inscrire</button>
    </div>

    @if($errors->any())
    <div class="alert alert-danger py-2 px-3 mb-3" style="border-radius:var(--radius-sm);font-size:.875rem;">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      <ul class="mb-0 ps-3" style="list-style:disc;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">
          Nom complet *
        </label>
        <div class="input-group">
          <span class="input-group-text" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-right:0;border-radius:var(--radius-sm) 0 0 var(--radius-sm);">
            <i class="bi bi-person"></i>
          </span>
          <input type="text" name="name"
                 class="form-control @error('name') is-invalid @enderror"
                 placeholder="Mohamed Amine Benali"
                 value="{{ old('name') }}" required autofocus
                 style="border:1.5px solid var(--cre-border);border-left:0;border-radius:0 var(--radius-sm) var(--radius-sm) 0;">
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">
          Téléphone +212 *
        </label>
        <div class="input-group">
          <span class="input-group-text" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-right:0;border-radius:var(--radius-sm) 0 0 var(--radius-sm);">
            <i class="bi bi-telephone"></i>
          </span>
          <input type="tel" name="phone"
                 class="form-control @error('phone') is-invalid @enderror"
                 placeholder="+212612345678"
                 value="{{ old('phone','+212') }}" required
                 pattern="^\+212[5-7][0-9]{8}$"
                 title="+212 suivi de 9 chiffres (ex: +212612345678)"
                 style="border:1.5px solid var(--cre-border);border-left:0;border-radius:0 var(--radius-sm) var(--radius-sm) 0;">
          @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-text" style="font-size:.75rem;">Format : +212XXXXXXXXX (ex: +212612345678)</div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">
          Mot de passe * <span class="text-muted fw-normal">(min. 6 caractères)</span>
        </label>
        <div class="input-group">
          <span class="input-group-text" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-right:0;border-radius:var(--radius-sm) 0 0 var(--radius-sm);">
            <i class="bi bi-lock"></i>
          </span>
          <input type="password" name="password" id="pwd"
                 class="form-control @error('password') is-invalid @enderror"
                 placeholder="••••••••" required minlength="6"
                 style="border:1.5px solid var(--cre-border);border-left:0;border-right:0;border-radius:0;">
          <button type="button" onclick="togglePwd('pwd','eyePwd')" class="input-group-text" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-left:0;border-radius:0 var(--radius-sm) var(--radius-sm) 0;cursor:pointer;">
            <i class="bi bi-eye" id="eyePwd"></i>
          </button>
          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">
          Confirmer le mot de passe *
        </label>
        <div class="input-group">
          <span class="input-group-text" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-right:0;border-radius:var(--radius-sm) 0 0 var(--radius-sm);">
            <i class="bi bi-lock-fill"></i>
          </span>
          <input type="password" name="password_confirmation" id="pwd2"
                 class="form-control"
                 placeholder="••••••••" required
                 style="border:1.5px solid var(--cre-border);border-left:0;border-right:0;border-radius:0;">
          <button type="button" onclick="togglePwd('pwd2','eyePwd2')" class="input-group-text" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-left:0;border-radius:0 var(--radius-sm) var(--radius-sm) 0;cursor:pointer;">
            <i class="bi bi-eye" id="eyePwd2"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-pardo-primary w-100 btn-lg" style="font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;letter-spacing:.05em;">
        CRÉER MON COMPTE
      </button>
    </form>

    <p class="text-center mt-4 text-muted" style="font-size:.875rem;">
      Déjà inscrit ?
      <a href="{{ route('login') }}" style="color:var(--cre-blue);font-weight:600;">Se connecter</a>
    </p>
  </div>
</div>
@endsection

@section('scripts')
<script>
function togglePwd(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon  = document.getElementById(iconId);
  input.type = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endsection
