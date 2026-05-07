<footer class="footer-pardo py-5 mt-5" style="background-color: #fff; background-image: linear-gradient(#f0f0f0 1px, transparent 1px), linear-gradient(90deg, #f0f0f0 1px, transparent 1px); background-size: 30px 30px; border-top: 1px solid #eaeaea;">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          <img src="{{ asset('images/logo.png') }}" alt="PARDOX" height="40" style="object-fit: contain;">
        </div>
        <p style="color:#555;font-size:.9rem;line-height:1.7;max-width:280px; font-weight:500;">
          La location de voitures premium, simplifiée. Prenez la route l'esprit tranquille.
        </p>
      </div>
      <div class="col-lg-2 col-6">
        <div class="footer-heading mb-3" style="color:#000;font-weight:800;font-size:.85rem;">SERVICES</div>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="{{ route('vehicles.index') }}" class="footer-link text-dark fw-medium text-decoration-none">Location courte durée</a></li>
          <li class="mb-2"><a href="#" class="footer-link text-dark fw-medium text-decoration-none">Location longue durée</a></li>
          <li class="mb-2"><a href="#" class="footer-link text-dark fw-medium text-decoration-none">Transfert aéroport</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-6">
        <div class="footer-heading mb-3" style="color:#000;font-weight:800;font-size:.85rem;">SOCIÉTÉ</div>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="footer-link text-dark fw-medium text-decoration-none">À propos</a></li>
          <li class="mb-2"><a href="#" class="footer-link text-dark fw-medium text-decoration-none">Conditions générales</a></li>
          <li class="mb-2"><a href="#" class="footer-link text-dark fw-medium text-decoration-none">Politique de confidentialité</a></li>
        </ul>
      </div>
      <div class="col-lg-4">
        <div class="footer-heading mb-3" style="color:#000;font-weight:800;font-size:.85rem;">SUPPORT</div>
        <ul class="list-unstyled">
          <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-telephone text-dark"></i><span class="footer-link text-dark fw-medium">+212 617-889657</span></li>
          <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-envelope text-dark"></i><a href="mailto:contact@pardox.ma" class="footer-link text-dark fw-medium text-decoration-none">contact@pardox.ma</a></li>
          <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-geo-alt text-dark"></i><span class="footer-link text-dark fw-medium">Agadir, Maroc</span></li>
          <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-clock text-dark"></i><span class="footer-link text-dark fw-medium">Lun–Dim 08h–20h</span></li>
        </ul>
      </div>
    </div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-5 bg-white p-3 border rounded shadow-sm">
      <span style="color:#555;font-size:.85rem;font-weight:600;">© {{ date('Y') }} PARDOX. Tous droits réservés.</span>
      <div class="d-flex gap-4">
        <a href="#" class="text-dark fs-5"><i class="bi bi-instagram"></i></a>
        <a href="#" class="text-dark fs-5"><i class="bi bi-linkedin"></i></a>
        <a href="#" class="text-dark fs-5"><i class="bi bi-twitter-x"></i></a>
        <a href="#" class="text-dark fs-5"><i class="bi bi-facebook"></i></a>
      </div>
    </div>
  </div>
</footer>
