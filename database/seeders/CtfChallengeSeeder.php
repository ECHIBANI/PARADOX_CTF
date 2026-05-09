<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\CtfChallenge;

/**
 * CtfChallengeSeeder
 * Peuple la table ctf_challenges avec les 6 challenges automobiles PARDOX CTF.
 * SÉCURITÉ : Le flag est stocké hashé avec Hash::make() — jamais en clair.
 */
class CtfChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $challenges = [
            [
                'title'       => 'CAN Bus Sniffer',
                'slug'        => 'can-bus-sniffer',
                'description' => 'Analysez le trafic du bus CAN d\'un véhicule pour identifier des messages intéressants et reconstituer la commande secrète.',
                'statement'   => "Un système embarqué contrôle plusieurs fonctions critiques du véhicule via le bus CAN.\nVous avez capturé le trafic réseau pendant une séquence de test.\nTrouvez et interprétez le message qui révèle la commande secrète.\n\nObjectifs :\n- Analyser le trafic CAN fourni\n- Identifier les messages pertinents\n- Décoder la charge utile du message cible\n- Reconstituer le flag au format PARDOX{...}\n\nScénario : Un ingénieur a laissé un message caché dans le bus CAN comme défi pour les curieux. Seul quelqu'un comprenant le protocole et les logs pourra le lire.\n\nNote : Le flag est sensible à la casse.",
                'category'    => 'Reverse',
                'difficulty'  => 'easy',
                'points'      => 100,
                'theme'       => 'Automobile',
                'flag_hash'   => Hash::make('PARDOX{CAN_BUS_MASTER}'),
                'author'      => 'pardox_dev',
                'attempts'    => 1248,
                'file_1'      => 'capture_canbus.log',
                'file_2'      => 'guide.pdf',
            ],
            [
                'title'       => 'Ahsan Madrassa',
                'slug'        => 'ahsan-madrassa',
                'description' => 'Le réseau Wi-Fi de la médiathèque universitaire Ahsan Madrassa a été compromis. Retrouvez le message chiffré laissé par l\'intrus dans les logs du système.',
                'statement'   => "Un intrus a pénétré le réseau Wi-Fi de la médiathèque de l'université Ahsan Madrassa.\nIl a laissé un message chiffré dissimulé dans les logs du système d'authentification.\nVotre mission : analyser les logs, identifier le message et le déchiffrer.\n\nObjectifs :\n- Analyser les fichiers de logs fournis\n- Identifier le message chiffré laissé par l'intrus\n- Décoder le message (chiffrement classique)\n- Reconstituer le flag au format PARDOX{...}\n\nScénario : La médiathèque de l'université reçoit des dizaines d'étudiants chaque jour. Un individu malveillant a profité d'une session ouverte pour s'introduire dans le système et laisser un défi cryptographique pour qui saura le trouver.\n\nNote : Commencez par chercher les entrées anormales dans les logs d'authentification.",
                'category'    => 'Crypto',
                'difficulty'  => 'easy',
                'points'      => 500,
                'theme'       => 'Madrassa',
                'image'       => 'ahsan-madrassa.png',
                'flag_hash'   => Hash::make('PARDOX{AHSAN_MADRASSA_DECODED}'),
                'author'      => 'pardox_dev',
                'attempts'    => 0,
            ],
            [
                'title'       => 'Garage Access',
                'slug'        => 'garage-access',
                'description' => 'Trouvez la vulnérabilité dans le système d\'accès au garage pour ouvrir la porte.',
                'statement'   => "Le système d'accès au garage d'un concessionnaire automobile utilise une interface web.\nUne faille de sécurité vous permettrait d'y accéder sans autorisation.\nIdentifiez et exploitez la vulnérabilité.\n\nObjectifs :\n- Analyser l'interface web du garage\n- Identifier la vulnérabilité\n- Exploiter la faille pour accéder au système\n- Récupérer le flag au format PARDOX{...}",
                'category'    => 'Web',
                'difficulty'  => 'easy',
                'points'      => 100,
                'theme'       => 'Automobile',
                'flag_hash'   => Hash::make('PARDOX{GARAGE_OPEN}'),
                'author'      => 'pardox_dev',
                'attempts'    => 2134,
            ],
            [
                'title'       => 'GPS Trace',
                'slug'        => 'gps-trace',
                'description' => 'Analysez les traces GPS d\'un véhicule suspect pour retrouver son itinéraire secret.',
                'statement'   => "Un véhicule suspect a été tracé via son GPS embarqué.\nLes données de trajet ont été capturées mais sont partiellement masquées.\nAnalysez les métadonnées et les coordonnées pour reconstituer l'itinéraire et trouver le flag.\n\nObjectifs :\n- Analyser les fichiers GPS fournis\n- Identifier les coordonnées significatives\n- Reconstruire l'itinéraire\n- Trouver le flag caché dans les métadonnées",
                'category'    => 'Forensics',
                'difficulty'  => 'medium',
                'points'      => 200,
                'theme'       => 'Automobile',
                'flag_hash'   => Hash::make('PARDOX{GPS_TRACKED}'),
                'author'      => 'pardox_dev',
                'attempts'    => 654,
            ],
            [
                'title'       => 'ECU Memory Dump',
                'slug'        => 'ecu-memory-dump',
                'description' => 'Analysez le dump mémoire de l\'ECU (Engine Control Unit) pour extraire la donnée secrète.',
                'statement'   => "Un dump complet de la mémoire de l'ECU d'un véhicule a été réalisé lors d'un audit de sécurité.\nDes données sensibles ont été cachées dans ce dump.\nVotre mission : analyser le binaire, identifier les sections pertinentes et extraire le flag.\n\nObjectifs :\n- Analyser le dump mémoire ECU\n- Identifier les zones mémoire intéressantes\n- Extraire et décoder les données cachées\n- Reconstituer le flag au format PARDOX{...}\n\nAvertissement : Ce challenge nécessite des outils d'analyse binaire.",
                'category'    => 'Pwn',
                'difficulty'  => 'hard',
                'points'      => 300,
                'theme'       => 'Automobile',
                'flag_hash'   => Hash::make('PARDOX{ECU_MEMORY_FOUND}'),
                'author'      => 'pardox_dev',
                'attempts'    => 312,
            ],
            [
                'title'       => 'Pit Stop SQL',
                'slug'        => 'pit-stop-sql',
                'description' => 'Exploitez la base de données de gestion d\'un pit stop de course automobile.',
                'statement'   => "Le système de gestion des pit stops d'une écurie de course utilise une base de données SQL.\nUne interface web mal sécurisée expose des données sensibles.\nUtilisez vos connaissances en injection SQL pour accéder aux informations cachées.\n\nObjectifs :\n- Analyser l'interface web de gestion\n- Identifier les points d'injection SQL\n- Exploiter la vulnérabilité\n- Extraire le flag de la base de données",
                'category'    => 'Web',
                'difficulty'  => 'medium',
                'points'      => 200,
                'theme'       => 'Automobile',
                'image'       => 'pit-stop-sql.png',
                'flag_hash'   => Hash::make('PARDOX{SQL_PIT_STOP}'),
                'author'      => 'pardox_dev',
                'attempts'    => 987,
            ],
            [
                'title'       => 'Dacia Backdoor',
                'slug'        => 'dacia-backdoor',
                'description' => 'Le portail web de diagnostic de la Dacia Spring embarquée cache une porte dérobée. Trouvez-la et extrayez le flag avant que le système ne se réinitialise.',
                'statement'   => "Un technicien a déployé en urgence un portail web de diagnostic sur une Dacia Spring connectée.\nFaute de temps, des raccourcis ont été pris lors du développement — et une porte dérobée a été laissée dans le code.\n\nObjectifs :\n- Analyser le portail web de diagnostic\n- Identifier la vulnérabilité critique\n- Contourner l'authentification via la backdoor\n- Accéder au panneau d'administration caché\n- Extraire le flag au format PARDOX{...}\n\nScénario : Une Dacia Spring d'un service de livraison expose son interface web de bord sur le réseau local. Un audit de sécurité vous mandate pour tester sa résistance. Aucune pièce n'a été épargnée sur ce véhicule — pas même la sécurité.\n\nNote : Le flag est sensible à la casse. Pensez aux headers HTTP non documentés.",
                'category'    => 'Web',
                'difficulty'  => 'hard',
                'points'      => 1000,
                'theme'       => 'Dacia',
                'flag_hash'   => Hash::make('PARDOX{DACIA_BACKDOOR_FOUND}'),
                'author'      => 'pardox_dev',
                'attempts'    => 0,
            ],

            // ─── Chofar Forensics Hard 1000 pts ───────────────────────────────
            [
                'title'       => 'Chofar Evidence',
                'slug'        => 'chofar-evidence',
                'description' => 'Un fichier image récupéré sur le disque dur d\'un chauffeur suspect contient des preuves numériques cachées. Extrayez-les avant leur destruction.',
                'statement'   => "Les autorités ont saisi le disque dur d'un chauffeur impliqué dans une affaire de fraude automobile.\nParmi les fichiers récupérés, une image JPG semble anodine — mais elle cache des données critiques.\n\nObjectifs :\n- Analyser le fichier image fourni\n- Identifier les techniques de dissimulation\n- Extraire les données cachées (métadonnées, EXIF, stégano)\n- Reconstituer le flag au format PARDOX{...}\n\nScénario : Le chauffeur 'Chofar' dissimulait des informations sensibles dans des fichiers image. Un expert forensics doit percer ses secrets.\n\nNote : Utilisez exiftool, strings, binwalk pour commencer.",
                'category'    => 'Forensics',
                'difficulty'  => 'hard',
                'points'      => 1000,
                'theme'       => 'Chofar',
                'image'       => 'chofar-evidence.png',
                'flag_hash'   => Hash::make('PARDOX{CHOFAR_EVIDENCE_FOUND}'),
                'author'      => 'pardox_dev',
                'attempts'    => 0,
            ],

            // ─── Crypto Facile 100 pts ────────────────────────────────────────
            [
                'title'       => 'Code Plaque',
                'slug'        => 'code-plaque',
                'description' => 'Un message chiffré a été retrouvé gravé sous une plaque d\'immatriculation. Décodez-le pour révéler le flag.',
                'statement'   => "Un technicien a découvert un message étrange gravé sous la plaque d'immatriculation d'un véhicule suspect.\nLe message semble être un simple texte, mais il est chiffré avec une méthode classique.\n\nObjectifs :\n- Analyser le message chiffré fourni\n- Identifier le type de chiffrement (substitution simple)\n- Déchiffrer le message\n- Reconstituer le flag au format PARDOX{...}\n\nNote : Pensez aux chiffrements classiques — César, ROT13, Vigenère...",
                'category'    => 'Crypto',
                'difficulty'  => 'easy',
                'points'      => 100,
                'theme'       => 'Automobile',
                'flag_hash'   => Hash::make('PARDOX{CODE_PLAQUE_DECODE}'),
                'author'      => 'pardox_dev',
                'attempts'    => 0,
            ],

            // ─── Crypto Moyen 200 pts ─────────────────────────────────────────
            [
                'title'       => 'OBD Cipher',
                'slug'        => 'obd-cipher',
                'description' => 'Les données OBD-II d\'un véhicule ont été interceptées et partiellement chiffrées. Cassez le chiffrement pour lire les codes cachés.',
                'statement'   => "Un système OBD-II transmet des données chiffrées vers un serveur distant.\nVous avez intercepté un paquet pendant une session de diagnostic.\nLe chiffrement utilisé est un algorithme hybride — mais avec une faiblesse connue.\n\nObjectifs :\n- Analyser les données OBD-II interceptées\n- Identifier la faiblesse dans l'implémentation\n- Déchiffrer le contenu du paquet\n- Extraire le flag au format PARDOX{...}\n\nNote : Étudiez la réutilisation des clés et les vecteurs d'initialisation (IV).",
                'category'    => 'Crypto',
                'difficulty'  => 'medium',
                'points'      => 200,
                'theme'       => 'Automobile',
                'flag_hash'   => Hash::make('PARDOX{OBD_CIPHER_CRACKED}'),
                'author'      => 'pardox_dev',
                'attempts'    => 0,
            ],

            // ─── Web Hard PARDOX 1000 pts ─────────────────────────────────────
            [
                'title'       => 'PARDOX Admin Panel',
                'slug'        => 'pardox-admin-panel',
                'description' => 'Le panneau d\'administration interne de PARDOX présente une faille critique. Trouvez-la et prouvez votre accès.',
                'statement'   => "PARDOX expose un panneau d'administration web pour la gestion de sa flotte.\nUn audit a révélé la présence d'une vulnérabilité critique dans l'interface.\n\nObjectifs :\n- Cartographier l'application web PARDOX Admin\n- Identifier les vulnérabilités (injection, IDOR, mauvaise session...)\n- Contourner l'authentification\n- Accéder aux données confidentielles\n- Extraire le flag au format PARDOX{...}\n\nScénario : PARDOX a déployé son panneau admin en urgence. La rapidité du déploiement a laissé des failles. En tant que pentesteur mandaté, trouvez-les avant les attaquants.\n\nNote : Attention aux injections, aux IDOR et aux mauvaises configurations de session.",
                'category'    => 'Web',
                'difficulty'  => 'hard',
                'points'      => 1000,
                'theme'       => 'PARDOX',
                'image'       => 'pardox-logo.png',
                'flag_hash'   => Hash::make('PARDOX{ADMIN_PANEL_PWNED}'),
                'author'      => 'pardox_dev',
                'attempts'    => 0,
            ],
        ];

        foreach ($challenges as $challengeData) {
            CtfChallenge::updateOrCreate(
                ['slug' => $challengeData['slug']],
                $challengeData
            );
        }

        $this->command->info('✅ ' . count($challenges) . ' challenges CTF PARDOX en base !');
    }
}
