@extends('layouts.app')
@section('title', 'Contact')

@section('content')
<section style="background:linear-gradient(105deg,#0f172a,#1a3a6b);padding:3rem 0">
    <div class="container">
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:2.5rem;color:#fff">NOUS CONTACTER</h1>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-7">
                <div style="background:#fff;border-radius:16px;padding:2rem;border:1px solid #e2e8f0;box-shadow:0 4px 20px rgba(0,0,0,.07)">
                    <h4 class="fw-bold mb-4">Envoyez-nous un message</h4>

                    @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger mb-4 small">
                        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                    </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Votre nom <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Sujet <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" rows="6" required>{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 px-4">
                            <i class="bi bi-send me-2"></i>Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div style="background:#f8faff;border-radius:16px;padding:1.5rem;border:1px solid #e2e8f0">
                    <h5 class="fw-bold mb-3">Infos de contact</h5>
                    <div class="mb-3 d-flex gap-2 align-items-start">
                        <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                        <div><div class="fw-semibold small">Adresse</div><div class="small text-muted">12 Rue de la Paix, 75001 Paris</div></div>
                    </div>
                    <div class="mb-3 d-flex gap-2 align-items-start">
                        <i class="bi bi-telephone-fill text-primary mt-1"></i>
                        <div><div class="fw-semibold small">Téléphone</div><div class="small text-muted">+33 1 00 00 00 00</div></div>
                    </div>
                    <div class="mb-3 d-flex gap-2 align-items-start">
                        <i class="bi bi-envelope-fill text-primary mt-1"></i>
                        <div><div class="fw-semibold small">Email</div><div class="small text-muted">contact@carrentexpress.fr</div></div>
                    </div>
                    <div class="d-flex gap-2 align-items-start">
                        <i class="bi bi-clock-fill text-primary mt-1"></i>
                        <div><div class="fw-semibold small">Horaires</div><div class="small text-muted">Lun-Sam : 8h-19h<br>Dim : 9h-17h</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
