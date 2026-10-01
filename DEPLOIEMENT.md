# Déploiement — environnement de démonstration local

Ce document décrit comment exposer temporairement l'instance locale de CareerGO
sur Internet pour une démonstration (tuteur, soutenance), en attendant un
hébergement payant. Ce n'est **pas** une mise en production : l'application
tourne toujours sur la machine locale, seule l'URL d'accès change.

## Principe

```
Navigateur externe → URL publique ngrok → tunnel ngrok → 127.0.0.1:8000 → php artisan serve
```

Le serveur PHP local et le tunnel ngrok doivent **rester allumés en continu**
pendant toute la durée de la démonstration : dès que l'un des deux processus
s'arrête (fermeture du terminal, mise en veille du PC, etc.), l'URL publique
devient inaccessible.

## Prérequis (déjà en place)

- `APP_DEBUG=false` dans `.env` (`APP_ENV` reste `local`) — évite d'exposer
  les traces d'erreur Laravel à un visiteur externe.
- Assets front compilés en mode production : `npm run build` (au lieu de
  `npm run dev`). Génère `public/build/`, servi tel quel — pas de rechargement
  à chaud, c'est normal en mode démo.
- ngrok installé dans `C:\Users\hp\ngrok\ngrok.exe`, avec l'authtoken du
  compte déjà configuré (`ngrok config add-authtoken ...`).
- `bootstrap/app.php` fait confiance aux en-têtes de proxy (`trustProxies`)
  pour que Laravel génère des URLs d'assets en `https://` derrière le tunnel
  ngrok (sans ça, la page reste blanche : le navigateur bloque les scripts
  `http://` chargés depuis une page `https://`).

## Procédure pour relancer la démo

1. **Si le code a changé depuis la dernière démo**, recompiler les assets :
   ```
   npm run build
   ```
   Vérifier qu'aucun fichier `public/hot` ne traîne (résidu d'un `npm run dev`
   précédent) — sa présence force Laravel à charger les assets depuis un
   serveur Vite qui n'existe plus. Le supprimer si besoin :
   ```
   rm public/hot
   ```

2. **Démarrer le serveur Laravel** (dans le dossier du projet, terminal à
   garder ouvert) :
   ```
   php artisan serve
   ```
   Il écoute par défaut sur `http://127.0.0.1:8000`.

3. **Dans un second terminal**, démarrer le tunnel ngrok :
   ```
   C:\Users\hp\ngrok\ngrok.exe http 8000
   ```
   La ligne `started tunnel ... url=https://xxxxx.ngrok-free.dev` donne
   l'URL publique à partager.

4. **Communiquer l'URL publique** affichée par ngrok (pas l'URL locale) à la
   personne qui doit accéder à la démo.

5. **Premier accès depuis un nouveau navigateur** : ngrok affiche une page
   d'avertissement interstitielle (« You are about to visit... »), normale
   sur le plan gratuit. Cliquer sur **Visit Site** pour continuer — cette
   page ne s'affiche qu'une fois par navigateur.

## ⚠️ Limite du compte gratuit ngrok

**L'URL publique change à chaque redémarrage du tunnel.** Il n'y a pas d'URL
fixe sur le plan gratuit : si `ngrok` est arrêté puis relancé, une nouvelle
URL aléatoire est générée (ex. `https://slighting-populate-referable.ngrok-free.dev`
un jour, une autre chaîne le lendemain). Il faut donc renvoyer la nouvelle
URL à chaque nouvelle session de démonstration.

## Comptes de démonstration

| Rôle | Email | Mot de passe |
|---|---|---|
| Candidat | `candidat@carreergo.test` | `password` |
| Recruteur | `recruteur@carreergo.test` | `password` |
| Admin | `admin@carreergo.test` | `password` |

(Créés via `DemoUsersSeeder` — `php artisan db:seed --class=DemoUsersSeeder`
si besoin de les recréer.)

## Contenu de démonstration

```
php artisan db:seed --class=DemoContentSeeder
```

À lancer après `DemoUsersSeeder`, dont il réutilise les comptes sans les
modifier. Il crée 4 entreprises fictives marquées « (démo) », 8 offres à
Libreville, Port-Gentil, Franceville et Oyem (CDI, CDD, stage, freelance ;
salaires en FCFA), leurs compétences et 5 candidatures du candidat de démo
aux statuts variés. Une offre reste « en attente » pour la démonstration de
la modération. Il relocalise aussi au Gabon les offres situées dans une
ville marocaine.

Le seeder est rejouable sans doublons ; le rejouer remet les offres et les
candidatures de démo dans leur état initial (statuts compris).

## Dépannage

- **Page blanche après connexion via l'URL ngrok** : signe que les assets
  sont chargés en `http://` au lieu de `https://` (mixed content bloqué par
  le navigateur). Vérifier que `bootstrap/app.php` contient bien
  `$middleware->trustProxies(at: '*');`.
- **`ngrok.exe` bloqué par Windows Defender** (« contient un virus ou un
  logiciel potentiellement indésirable ») : faux positif rencontré sur ce
  poste. Ajouter une exclusion via l'app **Sécurité Windows** (pas via
  PowerShell si la Protection contre les altérations est active) :
  Protection contre les virus et menaces → Gérer les paramètres →
  Exclusions → Ajouter une exclusion → Dossier → `C:\Users\hp\ngrok`.
- **Erreur de connexion à la base de données** : vérifier qu'un seul service
  MySQL écoute sur le port 3306 (conflit possible entre le MySQL de Laragon
  et un service Windows MySQL indépendant) et que les identifiants dans
  `.env` correspondent à l'instance active.

## Hors périmètre de cet environnement

- Pas de vrai hébergement distant à ce stade — uniquement l'instance locale
  exposée temporairement.
- Configuration session/cache inchangée (valeurs par défaut locales) : pas
  adaptée à un usage autre que ponctuel, mais suffisante pour une démo.
