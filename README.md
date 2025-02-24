# Documentation du Plugin Up Cache Control

## Description
Up Cache Control est un plugin WordPress qui permet de gérer et de désactiver les caches spécifiques liés à Gutenberg, ainsi que de vider automatiquement le cache lors des mises à jour.

## Fonctionnalités
- Désactivation du cache des patterns Gutenberg
- Désactivation du cache des styles de blocs
- Désactivation du cache des fonctionnalités Gutenberg
- Vidage automatique du cache après les mises à jour

## Installation
1. Téléchargez le fichier ZIP du plugin.
2. Dans votre administration WordPress, allez dans **Extensions > Ajouter > Téléverser une extension**.
3. Sélectionnez le fichier ZIP et cliquez sur **Installer maintenant**.
4. Activez le plugin.

## Configuration
1. Après activation, allez dans **Réglages > Up Cache**.
2. Cochez les options souhaitées :
   - **Désactiver le cache pour Gutenberg Patterns** : Empêche la mise en cache des patterns Gutenberg.
   - **Désactiver le cache pour les styles de blocs** : Empêche la mise en cache des styles de blocs.
   - **Désactiver le cache pour les fonctionnalités Gutenberg** : Empêche la mise en cache des fonctionnalités Gutenberg.
   - **Vider automatiquement le cache** : Vide le cache après chaque mise à jour (plugins, thèmes, ou WordPress).
3. Cliquez sur **Enregistrer les modifications**.

## Fonctionnement technique
### Transients concernés
Le plugin agit sur les transients suivants :
- `_wp_block_patterns_cache` : Cache des patterns Gutenberg.
- `_wp_block_pattern_categories_cache` : Cache des catégories de patterns Gutenberg.
- `_wp_block_styles_cache` : Cache des styles de blocs.
- `_wp_gutenberg_features` : Cache des fonctionnalités Gutenberg.

### Hooks utilisés
- `pre_set_transient_*` : Empêche la mise en cache des transients spécifiques.
- `upgrader_process_complete` : Déclenche le vidage du cache après une mise à jour.

## FAQ
### Pourquoi désactiver ces caches ?
Ces caches peuvent parfois causer des problèmes d'affichage ou empêcher les modifications récentes d'être visibles immédiatement. Les désactiver peut être utile en développement ou lors de modifications fréquentes.

### Le plugin affecte-t-il les performances ?
Désactiver ces caches peut légèrement augmenter le temps de chargement des pages, mais l'impact est généralement minime. Utilisez cette fonctionnalité avec précaution en production.

### Puis-je utiliser ce plugin avec un autre système de cache ?
Oui, ce plugin est compatible avec la plupart des systèmes de cache (WP Rocket, W3 Total Cache, etc.). Il ne désactive que les caches spécifiques à Gutenberg.

## Support
Pour toute question ou problème, veuillez ouvrir une issue sur [GitHub](https://github.com/votre-repo) ou contacter l'auteur.

## Auteur
Ce plugin a été développé par **GEHIN Nicolas**.

## Licence
Ce plugin est sous licence GPLv3. Utilisez-le librement et modifiez-le selon vos besoins.