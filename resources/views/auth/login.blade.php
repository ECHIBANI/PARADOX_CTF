@extends('layouts.app')
@section('title','Connexion — PARDOX')

@section('body')
<div class="auth-wrapper d-flex align-items-center justify-content-center p-3">
  <div class="auth-card">
    <div class="text-center mb-4">
      <img src="{{ asset('images/logo.png') }}" style="width: 150px; margin-bottom: 1.5rem;" alt="PARDOX">
      <p class="text-muted" style="font-size:.9rem;">Connectez-vous à votre compte</p>
    </div>

    {{-- Tab switch --}}
    <div class="tab-custom mb-4">
      <button class="tab-btn active">Connexion</button>
      <a href="{{ route('register') }}" class="tab-btn text-decoration-none text-center d-flex align-items-center justify-content-center">S'inscrire</a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger py-2 px-3 mb-3" style="border-radius:var(--radius-sm);font-size:.875rem;">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
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
                 value="{{ old('phone') }}"
                 required autofocus
                 style="border:1.5px solid var(--cre-border);border-left:0;border-radius:0 var(--radius-sm) var(--radius-sm) 0;">
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">
          Mot de passe *
        </label>
        <div class="input-group">
          <span class="input-group-text" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-right:0;border-radius:var(--radius-sm) 0 0 var(--radius-sm);">
            <i class="bi bi-lock"></i>
          </span>
          <input type="password" name="password"
                 class="form-control @error('password') is-invalid @enderror"
                 placeholder="••••••••" required
                 style="border:1.5px solid var(--cre-border);border-left:0;border-radius:0 var(--radius-sm) var(--radius-sm) 0;"
                 id="pwdInput">
          <button type="button" class="input-group-text" onclick="togglePwd()" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-left:0;border-radius:0 var(--radius-sm) var(--radius-sm) 0;cursor:pointer;">
            <i class="bi bi-eye" id="pwdIcon"></i>
          </button>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label" for="remember" style="font-size:.875rem;">Se souvenir de moi</label>
        </div>
      </div>
      <button type="submit" class="btn btn-pardo-primary w-100 btn-lg" style="font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;letter-spacing:.05em;">
        SE CONNECTER
      </button>
    </form>

    <p class="text-center mt-4 text-muted" style="font-size:.875rem;">
      Pas encore de compte ?
      <a href="{{ route('register') }}" style="color:var(--cre-blue);font-weight:600;">S'inscrire gratuitement</a>
    </p>


  </div>
</div>

@endsection

@section('scripts')
<script>
function togglePwd() {
  const input = document.getElementById('pwdInput');
  const icon  = document.getElementById('pwdIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'bi bi-eye-slash';
  } else {
    input.type = 'password';
    icon.className = 'bi bi-eye';
  }
}
</script>
@endsection
