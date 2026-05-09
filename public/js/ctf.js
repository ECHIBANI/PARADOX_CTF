/**
 * PARDOX CTF — ctf.js
 * JavaScript du module CTF : filtres, animations, notifications.
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── Filtrage temps réel des challenges par nom ──────────────
    const searchInput = document.getElementById('ctf-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.ctf-challenge-item').forEach(function (item) {
                const title = item.querySelector('.ctf-card-title')?.textContent.toLowerCase() || '';
                item.style.display = title.includes(q) ? '' : 'none';
            });
        });
    }

    // ── Animation hover sur les cards ──────────────────────────
    document.querySelectorAll('.ctf-card').forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            this.style.transition = 'all 0.3s ease';
        });
    });

    // ── Animation d'entrée progressive des cards ───────────────
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry, i) {
            if (entry.isIntersecting) {
                setTimeout(function () {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, i * 60);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.ctf-card, .ctf-leaderboard-row').forEach(function (el) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        observer.observe(el);
    });

    // ── Auto-fermeture des alertes flash après 8 secondes ──────
    const flash = document.getElementById('ctf-flash-alert');
    if (flash) {
        setTimeout(function () {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(function () { flash.remove(); }, 500);
        }, 8000);
    }

    // ── Notification visuelle après soumission correcte ─────────
    const resultBox = document.getElementById('ctf-flag-result-box');
    if (resultBox) {
        resultBox.style.animation = 'ctf-pulse-in 0.4s ease';
        setTimeout(function () {
            resultBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 200);
    }

    // ── Validation format du flag avant soumission ─────────────
    const flagForm = document.getElementById('ctf-flag-form');
    if (flagForm) {
        flagForm.addEventListener('submit', function (e) {
            const btn = document.getElementById('ctf-flag-submit');
            if (btn) {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Vérification...';
                btn.disabled = true;
            }
        });
    }

    // ── Copier le flag depuis input (double-clic) ───────────────
    const flagInput = document.getElementById('ctf-flag-input');
    if (flagInput) {
        flagInput.addEventListener('dblclick', function () { this.select(); });
    }

});

// ── Animation CSS injection ────────────────────────────────────
const style = document.createElement('style');
style.textContent = `
@keyframes ctf-pulse-in {
  0%   { transform: scale(0.97); opacity: 0; }
  100% { transform: scale(1);    opacity: 1; }
}
`;
document.head.appendChild(style);
