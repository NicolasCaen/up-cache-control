# Up Cache Control

## Description
**Up Cache Control** est un plugin WordPress permettant d'effacer différents types de cache directement depuis la barre d'administration. Il utilise désormais AJAX pour exécuter les actions sans recharger la page.

## Version
**1.5**

## Auteur
**Gehin Nicolas**

## Licence
GPL2 ou ultérieure

## Fonctionnalités
- Effacement du cache général de WordPress
- Effacement du cache de Gutenberg
- Suppression des transients
- Accès rapide aux options de cache via la barre d'administration
- Exécution des actions en AJAX pour une meilleure expérience utilisateur

## Installation
1. Téléchargez le fichier ZIP du plugin.
2. Décompressez le fichier et placez le dossier `up-cache-control` dans le répertoire `wp-content/plugins/` de votre site WordPress.
3. Activez le plugin via l'interface d'administration de WordPress.

## Utilisation
Une icône "😎" s'affiche dans la barre d'administration. En cliquant dessus, vous pouvez :
- Effacer le cache général 😡
- Effacer le cache de Gutenberg 😎
- Supprimer les transients 😛

## Fonctionnement AJAX
- Chaque bouton déclenche une requête AJAX vers `admin-ajax.php` avec les paramètres nécessaires.
- Le plugin vérifie les permissions et exécute l’action demandée sans recharger la page.

## Développement
- Possibilité d'ajouter des actions de cache personnalisées via le hook `up_cache_control_actions`.
- Code optimisé pour la compatibilité avec les bonnes pratiques WordPress.

## Changelog
### 1.4
- **Migration complète vers AJAX** : Suppression de la page dédiée au plugin
- **Refonte des actions** : Exécution des actions via `admin-ajax.php`
- **Correction de bugs** : Gestion des erreurs améliorée et validation des actions

## Support
En cas de problème, ouvrez une issue ou contactez l’auteur.

