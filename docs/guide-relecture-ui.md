# Guide de relecture — refonte visuelle « Équatorial »

Branche : `feat/refonte-visuelle-equatorial` (non fusionnée dans `master`).

| Commit | Contenu |
|---|---|
| `3ec1ac4` | Suppression des commentaires du code source (aucun changement de code) |
| `bed04e2` | `ExampleTest` aligné sur la redirection de `/` (Prompt 17) |
| `3b29d87` | Refonte visuelle (lots 1 à 4) |
| `d427a72` | Maquettes de référence dans `docs/maquettes/` |
| `aed03dd` | Messages du serveur en français |
| `35701a9` | Salaire masqué retiré des données envoyées aux candidats |

Les quatre lots ont été réalisés dans le même commit `3b29d87` ; le découpage ci-dessous sert à la relecture.

Règles suivies partout : aucune route, migration, règle d'autorisation ni nom de champ modifié ; aucune
dépendance ajoutée ; aucun chiffre inventé (tous les nombres affichés sont calculés à partir des
données envoyées par le serveur) ; l'or n'est jamais une couleur de texte sur fond clair.

---

## Lot 1 — Fondations, accueil, authentification

### Fondations

**`tailwind.config.js`**
Ajoute les jetons `cream` (#FBF7EE), `sand` (#F1E9D8) et `ink` (#1B2B25), ainsi que l'ombre `shadow-card`.
Les palettes `primary` (vert), `secondary` (or) et `tertiary` (bleu) existaient déjà (Prompt 20).
Tout le reste du code utilise ces noms, jamais de couleur codée en dur.

**`resources/css/app.css`**
Fond crème et texte encre sur `body`, police de titre sur `h1`–`h6`.
Contour de focus visible global (`:focus-visible`) et neutralisation des animations si `prefers-reduced-motion`.
Définit `.ui-input` (style commun des champs) et `.ui-label-caps` (petites étiquettes en capitales).

**`resources/views/app.blade.php`**
Charge seulement Inter 400/600 et Work Sans 700 (3 graisses au lieu de 7) avec `display=swap`.
Ajoute `theme-color` (barre du navigateur mobile en vert forêt).
Titre par défaut « CareerGO » au lieu de « Laravel ».

**`resources/js/app.js`**
Nom d'application par défaut « CareerGO ».
Barre de progression Inertia en or (élément graphique, pas du texte).
Aucune autre logique modifiée.

**`resources/js/utils/format.js`**
Formatage en français : dates, temps relatif (« il y a 2 jours »), montants.
`formatSalaryRange` affiche « FCFA / mois » ; `isRecent` décide du badge « Nouveau » (moins de 7 jours).
`initials` produit les initiales utilisées par `Avatar`.

**`resources/js/utils/status.js`**
Table unique des libellés et tons de statut (candidatures, offres, rôles).
Évite de répéter `pending → En attente…` dans chaque page.
Liste des types de contrat partagée entre la recherche et le formulaire d'offre.

### Composants Breeze restylés

**`Components/TextInput.vue`**
Utilise la classe `.ui-input` ; comportement inchangé (`autofocus`, `focus()` exposé).

**`Components/InputLabel.vue`**
Libellé en encre et graisse semi-grasse pour une meilleure lisibilité.

**`Components/InputError.vue`**
Rouge plus foncé (`red-700`, contraste AA) et `role="alert"` pour l'annonce par les lecteurs d'écran.

**`Components/Checkbox.vue`**
Case plus grande (20 px) avec la couleur primaire ; logique `v-model:checked` inchangée.

**`Components/PrimaryButton.vue`, `SecondaryButton.vue`, `DangerButton.vue`**
Même apparence que `ui/Button` (44 px de haut, coins arrondis, plus de majuscules espacées).
Conservés pour compatibilité ; les nouvelles pages utilisent `ui/Button`.

**`Components/Dropdown.vue`, `DropdownLink.vue`**
Menu aux coins arrondis, survol sable ; les liens peuvent contenir une icône.

**`Components/Modal.vue`**
Fond assombri à l'encre et coins arrondis ; logique d'ouverture et de fermeture inchangée.

### Layouts et pages

**`Layouts/AuthenticatedLayout.vue`**
Barre latérale sur ordinateur, barre de navigation en bas sur mobile, menu du compte en haut.
Les liens dépendent du rôle (candidat, recruteur, admin) ; `aria-current="page"` sur le lien actif.
Contient le lien « Aller au contenu » et `FlashMessages`, une seule fois pour toutes les pages.

**`Layouts/GuestLayout.vue`**
Panneau vert de présentation sur grand écran (texte explicatif, aucun chiffre), formulaire à droite.
Accepte un `title` et une `description` pour chaque page d'authentification.
Sur mobile, seul le logo et le formulaire sont affichés.

**`Pages/Dashboard.vue`**
Page d'arrivée après connexion : « Bonjour {prénom} » et trois raccourcis selon le rôle.
Aucun compteur : uniquement des liens vers les vraies pages.
Remplace l'ancien « You're logged in! ».

**`Pages/Auth/Login.vue`**
Formulaire traduit, lien « Mot de passe oublié ? » et lien vers l'inscription.
Affiche le message de suspension (`flash.error`) via `FlashMessages`.
Champs envoyés inchangés : `email`, `password`, `remember`.

**`Pages/Auth/Register.vue`**
Choix du rôle en pastilles (`ChoiceChips`) en tête du formulaire.
Champ « Nom de l'entreprise » affiché seulement pour un recruteur, comme avant.
Champs envoyés inchangés, dont `role` et `company_name`.

**`Pages/Auth/ForgotPassword.vue`, `ResetPassword.vue`, `ConfirmPassword.vue`, `VerifyEmail.vue`**
Traduction complète et mise en forme commune.
Aucun changement de route ni de champ.
Les messages d'état renvoyés par le serveur sont traduits par `lang/fr`.

### Traductions et test (commits séparés)

**`lang/fr/*.php`, `lang/fr.json`**
Messages de validation, de connexion, de mot de passe, d'e-mails et de pages d'erreur en français.
Les noms de champs sont lisibles (« Le champ adresse e-mail est obligatoire. »).
Deux messages ont une formulation dédiée : date de fin et salaire maximum.

**`config/app.php`, `.env.example`**
Langue par défaut `fr` (et `APP_LOCALE=fr` dans le `.env` local, non versionné).

**`tests/Feature/ExampleTest.php`**
Vérifie qu'un invité qui ouvre `/` est redirigé vers `/login`.
Corrige un test resté à l'attendu Breeze (code 200) après le Prompt 17.

---

## Lot 2 — Candidat

**`Pages/Jobs/Index.vue`** (maquette B6a)
Recherche par poste, filtres repliables (ville) et pastilles de type de contrat qui relancent la recherche.
Cartes d'offres : badges « Nouveau », « Réponse garantie », « Entreprise vérifiée », salaire si affiché.
Les paramètres envoyés restent `keyword`, `location`, `contract_type`.

**`Pages/Jobs/Show.vue`** (maquette B6b)
Détail de l'offre, encadré de compatibilité réservé aux candidats, compétences requises.
Bouton « Postuler en un clic » fixé en bas sur mobile, au-dessus de la navigation.
Si l'offre ne liste aucune compétence, aucun score n'est affiché (le serveur renverrait 0 %).

**`Pages/Applications/Index.vue`** (maquette B6c)
Onglets Toutes / En cours / Terminées, filtrés côté client.
Deux compteurs réels : candidatures envoyées et candidatures au stade de l'entretien.
Date affichée : envoi, ou dernière mise à jour pour une candidature terminée.

**`Pages/Profile/Edit.vue`** (maquette B6d)
En-tête avec initiales, nom, e-mail et rôle.
Pour un candidat : CV en PDF, compétences, expériences ; pour tous : compte, mot de passe, suppression.
Remplace l'ancien titre anglais « Profile ».

**`Pages/Profile/Partials/UpdateSkillsForm.vue`**
Utilise `TagInput` ; chaque ajout ou retrait enregistre immédiatement, comme avant.
Même requête `PUT profile.skills.update` avec un tableau `skills`.
Message « Compétences enregistrées » après succès.

**`Pages/Profile/Partials/UpdateExperiencesForm.vue`**
Liste des expériences avec suppression par icône (libellé accessible).
Formulaire d'ajout replié derrière « Ajouter une expérience ».
Champs inchangés : `title`, `company_name`, `start_date`, `end_date`.

**`Pages/Profile/Partials/UpdateProfileInformationForm.vue`, `UpdatePasswordForm.vue`, `DeleteUserForm.vue`**
Traduction complète des textes anglais d'origine (Breeze).
Mêmes routes et mêmes champs.
La suppression du compte reste protégée par mot de passe dans une fenêtre modale.

**`app/Models/JobOffer.php`, `JobOfferSearchController.php`, `ApplicationController.php`** (commit `35701a9`)
`hideSalaryUnlessShown()` retire `salary_min` et `salary_max` quand `show_salary` est faux.
Appliqué à la recherche, au détail et à « Mes candidatures » ; le recruteur les voit toujours en modification.
Couvert par `tests/Feature/JobOfferSalaryVisibilityTest.php` (3 tests).

---

## Lot 3 — Recruteur

**`Components/JobOfferForm.vue`** (maquette B5)
Formulaire partagé entre création et modification, découpé en sections avec aperçu en direct.
Compétences en pastilles (`TagInput`), contrat en pastilles (`ChoiceChips`), affichage du salaire en `Toggle`.
Reçoit l'objet `useForm` de la page : chaque page garde sa route et sa méthode (POST ou PUT).

**`Pages/JobOffers/Create.vue`**
Bouton « Soumettre l'offre » (et non « Publier ») : l'offre part en modération.
`skills` est désormais un tableau dès la saisie ; la requête envoyée a le même format qu'avant.
Affiche la mention de vérification avant publication.

**`Pages/JobOffers/Edit.vue`**
Même formulaire, prérempli à partir de l'offre.
Pas de mention de modération : la modification ne change pas le statut.
Requête `PUT job-offers.update` inchangée.

**`Pages/JobOffers/Index.vue`**
Liste « Mes offres » en cartes avec le statut traduit (« En attente de modération », « Publiée »…).
Actions Modifier et Supprimer (confirmation conservée).
État vide avec un appel à publier une première offre.

**`Pages/Recruiter/Applications/Index.vue`** (maquette B4)
Pipeline à 5 colonnes (au lieu de 3) : les candidatures acceptées et refusées restent visibles.
Le statut se change par une liste déroulante libellée (pas de glisser-déposer).
Score de compatibilité et ancienneté de la candidature sur chaque carte.

---

## Lot 4 — Admin, démo, finitions

**`Pages/Admin/Moderation/Index.vue`** (maquette B3)
Tableau sur ordinateur, cartes sur mobile ; actions Valider et Refuser.
Un seul compteur réel : les offres en attente.
Encadré « Guide de modération » décrivant ce que fait réellement chaque action.

**`Pages/Admin/Users/Index.vue`** (maquette B2)
Recherche et filtres par rôle et par statut, appliqués côté client sur la liste reçue.
Trois compteurs calculés : inscriptions du mois, entreprises vérifiées, comptes suspendus.
État de l'entreprise (badge) séparé de l'action (« Vérifier l'entreprise » ou « Retirer la vérification »).

**`Pages/Admin/Reports/Index.vue`**
Chaque signalement en carte avec le motif cité.
Actions « Marquer comme traité » et « Rejeter ».
Compteur réel des signalements en attente.

**`docs/maquettes/`** (commit `d427a72`)
Les 9 maquettes de référence (B1 à B6d), versionnées pour la relecture.

**Reste à faire dans ce lot**
- Données de démonstration : `DemoUsersSeeder` ne crée que trois comptes et une entreprise, sans offre.
- Vérification visuelle dans un navigateur, sur mobile et sur ordinateur.
- Suppression de `NavLink.vue` et `ResponsiveNavLink.vue`, qui ne sont plus utilisés.
- Non retouchés : `Pages/Welcome.vue` (aucune route ne l'affiche) et `views/pdf/cv.blade.php`.

---

## Composants `resources/js/Components/ui`

### `Icon`
**Rôle :** jeu d'icônes SVG intégré au code, avec une prop `size` (xs, sm, md, lg).
**Utilisé dans :** la plupart des pages, les layouts et plusieurs composants `ui`.
**Piège :** un nom inconnu affiche un SVG vide sans erreur ; et comme l'icône est `aria-hidden`, un bouton qui ne contient qu'une icône doit avoir un texte `sr-only`.

### `Button`
**Rôle :** bouton unique pour toutes les variantes (primary, secondary, inverted, outlined, accent, tertiary, danger, ghost).
**Utilisé dans :** auth, profil, offres, candidatures, modération, signalements, layout, `JobOfferForm`.
**Piège :** avec `href`, il devient un lien Inertia ; pour un fichier (le CV en PDF) il faut `native`, sinon Inertia tente une navigation XHR. `disabled` n'a aucun effet sur un lien.

### `Badge`
**Rôle :** étiquette de statut, avec un point ou une icône en option.
**Utilisé dans :** recherche et détail d'offre, candidatures, mes offres, pipeline, admin, profil, `JobOfferForm`.
**Piège :** un `tone` absent de la table donne un badge sans couleur ; le ton `accent` met du texte encre sur or, il ne faut jamais l'inverser.

### `Card`
**Rôle :** surface blanche arrondie avec trois niveaux de marge intérieure.
**Utilisé dans :** `Jobs/Show`, `Profile/Edit`, `JobOfferForm`.
**Piège :** une classe `p-…` passée en plus de la prop `padding` entre en conflit ; il faut passer par la prop.

### `PageHeader`
**Rôle :** h1, sur-titre, description et zone d'actions de la page.
**Utilisé dans :** accueil, offres, candidatures, mes offres, création et modification d'offre, pipeline, pages admin.
**Piège :** il produit un `h1` ; l'utiliser deux fois sur une page, ou en plus d'un autre `h1` (le profil a le sien), casse la hiérarchie des titres.

### `Avatar`
**Rôle :** initiales sur une pastille colorée, à la place des photos et logos absents de la base.
**Utilisé dans :** layout, recherche et détail d'offre, candidatures, pipeline, profil, pages admin.
**Piège :** il est `aria-hidden` ; le nom doit donc être écrit en texte à côté. La couleur dépend des caractères du nom, pas de la personne.

### `ProgressBar`
**Rôle :** barre accessible (`role="progressbar"`), valeur bornée entre 0 et 100.
**Utilisé dans :** `Jobs/Show` (score de compatibilité).
**Piège :** le serveur renvoie 0 quand l'offre n'a aucune compétence ; la page masque alors la barre pour ne pas afficher un faux « 0 % ».

### `StatCard`
**Rôle :** tuile chiffre et libellé, en quatre tons.
**Utilisé dans :** `Applications/Index`, pages admin (modération, utilisateurs, signalements).
**Piège :** les compteurs sont calculés sur la liste reçue ; si une pagination est ajoutée côté serveur, ils deviendront partiels et faux.

### `EmptyState`
**Rôle :** message et action quand une liste est vide.
**Utilisé dans :** recherche, candidatures, mes offres, pipeline, pages admin.
**Piège :** distinguer « aucune donnée » de « aucun résultat pour ces filtres » (la recherche propose alors « Effacer les filtres »).

### `FlashMessages`
**Rôle :** affiche `flash.success` (`role="status"`) et `flash.error` (`role="alert"`).
**Utilisé dans :** `AuthenticatedLayout`, `Auth/Login`.
**Piège :** il est déjà dans le layout connecté ; l'ajouter aussi dans une page affiche le message deux fois. `GuestLayout` ne l'inclut pas, d'où son ajout manuel dans `Login`.

### `Tabs`
**Rôle :** rangée de boutons `aria-pressed` avec compteur, qui filtre une liste déjà chargée.
**Utilisé dans :** `Applications/Index`.
**Piège :** ce ne sont pas de vrais onglets ARIA (pas de navigation aux flèches) ; ils sont annoncés comme des boutons. Le filtre ne fait aucun appel au serveur.

### `ChoiceChips`
**Rôle :** vrais boutons radio présentés en pastilles, dans un `fieldset` avec `legend`.
**Utilisé dans :** `Jobs/Index`, `Auth/Register`, `JobOfferForm`.
**Piège :** `name` doit être unique sur la page ; dans la recherche, l'événement `select` relance la requête et suppose que `v-model` a déjà mis la valeur à jour.

### `TagInput`
**Rôle :** saisie de pastilles (Entrée pour ajouter, croix pour retirer), `v-model` sur un tableau.
**Utilisé dans :** `JobOfferForm`, `UpdateSkillsForm`.
**Piège :** Entrée est bloquée pour ne pas soumettre le formulaire parent ; dans le profil, chaque modification envoie une requête au serveur.

### `Toggle`
**Rôle :** interrupteur `role="switch"` relié à son libellé et à sa description.
**Utilisé dans :** `JobOfferForm` (afficher le salaire).
**Piège :** c'est un bouton et non une case à cocher ; il ne fait pas partie des données natives du formulaire, seul le `v-model` alimente `show_salary`. Il doit rester `type="button"` pour ne pas soumettre le formulaire.

---

## 10 questions qu'un jury pourrait poser sur cette refonte

1. Comment garantissez-vous qu'aucun chiffre affiché n'est inventé, alors que les maquettes en contiennent beaucoup ?
2. Pourquoi l'or n'est-il jamais utilisé comme couleur de texte, et comment avez-vous vérifié les contrastes ?
3. Pourquoi créer des composants `ui` plutôt que de styliser chaque page directement ?
4. Comment la refonte prend-elle en compte les connexions lentes (polices, images, dépendances) ?
5. Quelles mesures d'accessibilité avez-vous mises en place, et lesquelles restent imparfaites (par exemple les onglets) ?
6. Pourquoi le bouton du recruteur dit « Soumettre l'offre » et non « Publier », comme dans la maquette ?
7. Pourquoi le salaire masqué posait-il un problème même s'il n'était pas affiché, et comment l'avez-vous testé ?
8. Comment avez-vous prouvé que la suppression des commentaires n'a changé aucun comportement ?
9. Pourquoi les filtres et les compteurs de l'administration sont-ils calculés côté client, et quelle est la limite de ce choix ?
10. Pourquoi un test a-t-il dû être corrigé, et pourquoi dans un commit séparé de la refonte ?
