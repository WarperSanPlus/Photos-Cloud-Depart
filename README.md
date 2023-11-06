# TO DO
- [ ] Clean components by set 'user-select' to 'none'.
- [ ] Afficher la date de création selon l'horaire local
- [ ] Specialize errorPage.php
- [ ] Check if the different page's menu's options are appropriate
- [ ] Move title from viewPhoto.php from 'title bar' to 'content'.
- [ ] Scale pictures depending on screen size

# WIP
- [ ] Empêcher l’accès illégal des pages réservées aux admins[^2].
- [x] Donner accès à la fonction octroyer/enlever le droit administrateur[^1].
- [x] Donner accès à la fonction bloquer/débloquer[^1].
- [x] Voir mes photos (photos de l’usager connecté)[^4].

# DONE
- [x] Produire la page qui affiche la liste des usagers (Gestion des usagers, excluant l’usager admin connecté).
- [x] Réserver l’accès à la liste des usagers aux usagers de type admin.
- [x] Faire apparaitre l’article de menu Gestion des usagers uniquement aux usagers administrateur. La page de gestion des usagers devra présenter tous les usagers, excluant celui qui est connecté.
- [x] Empêcher la connexion des usagers bloqués avec le message approprié.
- [x] Permettre aux usagers admins l’accès total aux photos de tous les usagers.
- [x] Tri des photos par leur date de création (décroissante).
- [x] Tri des photos par créateur (ordre ascendant de noms de créateurs).
- [X] Régler la suppression de photo
- [X] Régler la modification de photo
- [X] Ajouter une page qui affiche les détails d’une photo (Avatar du créateur, nom du créateur, titre, image de la photo de taille réactive,
description de largeur réactive, date et heure de création en français). Cette page sera obtenue en cliquant sur la photo dans la liste de photos.
- [X] Donner accès à l'effacement d'un usager avec confirmation de retrait.
- [X] Evaluate if any 'include' should be 'require' instead 
- [X] Center the avatar on deleteProfil.php
- [X] Comment the super admin mention in usersList.php 
- [X] Make the admin able to modify all pictures

[^1]: À optimiser
[^2]: Fait dans les pages déjà faites
[^4]: Fonctionnalité optionnelle à confirmer
