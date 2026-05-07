# 🚗 PARDOX — Site de Location de Véhicules
**Laravel 11 · MySQL · Bootstrap 5**

---

## 📦 Stack Technique

| Couche       | Technologie                         |
|-------------|--------------------------------------|
| Backend     | PHP 8.2 · Laravel 11                |
| Base de données | MySQL 8+ (ou MariaDB 10.6+)     |
| Frontend    | Bootstrap 5.3 · Bootstrap Icons     |
| Fonts       | Barlow Condensed + DM Sans (Google) |
| PDF (optionnel) | barryvdh/laravel-dompdf         |

---

## ⚡ Installation rapide

### 1. Prérequis
```bash
php >= 8.2
composer >= 2.x
MySQL 8+ ou MariaDB 10.6+
```

### 2. Cloner / extraire le projet
```bash
cd /var/www/html   # ou votre dossier web
# Extraire l'archive ZIP ici
cd pardo-loc
```

### 3. Installer les dépendances
```bash
composer install
```

### 4. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

Éditer `.env` :
```env
DB_DATABASE=pardo_loc
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
APP_URL=http://localhost:8000
```

### 5. Créer la base de données MySQL
```sql
CREATE DATABASE pardo_loc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Migrations + Seeders
```bash
php artisan migrate --seed
```

Cela crée :
- ✅ Tables : `users`, `vehicles`, `reservations`
- ✅ Admin : `+212600000000` / `admin123`
- ✅ Client démo : `+212661234567` / `user123`
- ✅ 6 véhicules exemples

### 7. Storage link (pour les images uploadées)
```bash
php artisan storage:link
```

### 8. Lancer le serveur de développement
```bash
php artisan serve
```

→ Ouvrir : **http://localhost:8000**

---

## 🗄️ Structure Base de Données

### Table `users`
| Colonne     | Type              | Description                      |
|-------------|-------------------|----------------------------------|
| id          | BIGINT PK         | Auto-increment                   |
| name        | VARCHAR(100)      | Nom complet                      |
| phone       | VARCHAR(20) UNIQUE| Format +212XXXXXXXXX             |
| password    | VARCHAR(255)      | Hash bcrypt                      |
| role        | ENUM(user,admin)  | Rôle                             |
| blocked     | BOOLEAN           | Compte bloqué                    |
| created_at  | TIMESTAMP         |                                  |

### Table `vehicles`
| Colonne        | Type             | Description            |
|----------------|------------------|------------------------|
| id             | BIGINT PK        |                        |
| name           | VARCHAR(100)     | ex: Toyota RAV4        |
| category       | VARCHAR(50)      | Économique, SUV, etc.  |
| price_per_day  | INT UNSIGNED     | Prix en DH             |
| seats          | TINYINT          | Nombre de places       |
| transmission   | ENUM             | Manuelle/Automatique   |
| ac             | BOOLEAN          | Climatisation          |
| image          | VARCHAR(255)     | URL ou chemin fichier  |
| available      | BOOLEAN          | Visible en front       |
| description    | TEXT             | Optionnel              |

### Table `reservations`
| Colonne              | Type                              | Description              |
|----------------------|-----------------------------------|--------------------------|
| id                   | BIGINT PK                        |                          |
| reservation_number   | VARCHAR(20) UNIQUE               | RES-YYYYMMDD-XXXXX       |
| user_id              | FK → users                       |                          |
| vehicle_id           | FK → vehicles                    |                          |
| start_date           | DATE                             | Début de location        |
| end_date             | DATE                             | Fin de location          |
| total_price          | INT UNSIGNED                     | Montant total DH         |
| acompte              | INT UNSIGNED                     | Acompte 30% DH           |
| status               | ENUM(pending,confirmed,rejected,completed) | Statut        |
| admin_note           | TEXT NULL                        | Note admin               |
| created_at           | TIMESTAMP                        |                          |

---

## 🔒 Algorithme Anti-Chevauchement (Anti-Overlap)

La vérification se fait **en double** :

### Backend (Model `Vehicle.php`)
```php
public function isAvailableFor(string $startDate, string $endDate): bool
{
    return $this->reservations()
        ->whereNotIn('status', ['rejected', 'completed'])
        ->where('start_date', '<', $endDate)   // new_start < existing_end
        ->where('end_date',   '>', $startDate) // new_end > existing_start
        ->doesntExist();
}
```

**Condition de refus :** `new_start < existing_end AND new_end > existing_start`

### Frontend (JavaScript)
- Calcul du prix en temps réel sans rechargement de page
- Désactivation visuelle des dates déjà réservées

---

## 🛣️ Routes Principales

### Public
| Route                       | Description              |
|-----------------------------|--------------------------|
| GET /                       | Page d'accueil           |
| GET /vehicules              | Liste des véhicules      |
| GET /vehicules/{id}         | Détail + formulaire résa |

### Authentification
| Route                       | Description              |
|-----------------------------|--------------------------|
| GET  /connexion             | Formulaire connexion     |
| POST /connexion             | Traitement connexion     |
| GET  /inscription           | Formulaire inscription   |
| POST /inscription           | Création compte          |
| POST /deconnexion           | Déconnexion              |

### Client (auth requis)
| Route                            | Description              |
|----------------------------------|--------------------------|
| POST /vehicules/{id}/reserver    | Créer une réservation    |
| GET  /reservation/{id}/bon       | Afficher le bon          |
| GET  /mes-reservations           | Mes réservations         |

### Admin (admin requis)
| Route                                    | Description             |
|------------------------------------------|-------------------------|
| GET  /admin                              | Dashboard               |
| GET  /admin/reservations                 | Liste réservations      |
| PATCH /admin/reservations/{id}/status   | Changer statut          |
| GET  /admin/vehicules                   | Liste véhicules         |
| GET  /admin/vehicules/ajouter           | Form ajout              |
| POST /admin/vehicules                   | Créer véhicule          |
| GET  /admin/vehicules/{id}/modifier     | Form modification       |
| PUT  /admin/vehicules/{id}              | Modifier véhicule       |
| DELETE /admin/vehicules/{id}            | Supprimer               |
| GET  /admin/utilisateurs                | Liste utilisateurs      |
| PATCH /admin/utilisateurs/{id}/bloquer  | Bloquer/débloquer       |

---

## 📄 Génération PDF (optionnel)

Installer dompdf :
```bash
composer require barryvdh/laravel-dompdf
```

Puis dans le contrôleur, remplacer la vue par :
```php
use Barryvdh\DomPDF\Facade\Pdf;

public function voucherPdf(Reservation $reservation) {
    $reservation->load(['user','vehicle']);
    $pdf = Pdf::loadView('front.voucher', compact('reservation'));
    return $pdf->download('bon-'.$reservation->reservation_number.'.pdf');
}
```

---

## 🔐 Sécurité

- ✅ Hash bcrypt pour les mots de passe
- ✅ Protection CSRF sur tous les formulaires POST/PUT/DELETE
- ✅ Validation côté serveur (Request::validate)
- ✅ Sanitisation XSS : strip_tags sur les champs texte
- ✅ Middleware auth + admin sur toutes les routes protégées
- ✅ Vérification propriétaire avant affichage du bon
- ✅ Regex validation téléphone +212XXXXXXXXX
- ✅ Anti-overlap strict côté backend (non contournable)
- ✅ Comptes bloqués : déconnexion immédiate

---

## 🚀 Déploiement Production

```bash
# Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.ma
```

### Configuration Apache (.htaccess déjà inclus dans /public)
Pointer le DocumentRoot vers `/public`

### Configuration Nginx
```nginx
server {
    listen 80;
    server_name pardox.ma www.pardox.ma;
    root /var/www/pardo-loc/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 📁 Arborescence du Projet

```
pardo-loc/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── HomeController.php
│   │   │   ├── VehicleController.php
│   │   │   ├── ReservationController.php
│   │   │   └── AdminController.php
│   │   └── Middleware/
│   │       ├── AdminOnly.php
│   │       └── CheckNotBlocked.php
│   └── Models/
│       ├── User.php
│       ├── Vehicle.php
│       └── Reservation.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_vehicles_table.php
│   │   └── ..._create_reservations_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php      ← Layout principal
│   │   ├── admin.blade.php    ← Layout admin
│   │   ├── navbar.blade.php
│   │   └── footer.blade.php
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── front/
│   │   ├── home.blade.php
│   │   ├── vehicles.blade.php
│   │   ├── vehicle-detail.blade.php
│   │   ├── voucher.blade.php
│   │   └── partials/
│   │       └── vehicle-card.blade.php
│   ├── client/
│   │   └── my-reservations.blade.php
│   └── admin/
│       ├── dashboard.blade.php
│       ├── reservations/
│       │   └── index.blade.php
│       ├── vehicles/
│       │   ├── index.blade.php
│       │   └── form.blade.php
│       └── users/
│           └── index.blade.php
├── routes/
│   └── web.php
├── bootstrap/
│   └── app.php
├── .env.example
├── composer.json
└── README.md
```
