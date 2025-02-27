## Up Cache Control

### Description

Up Cache Control est une extension WordPress qui fournit une interface centralisée pour gérer et vider différents types de caches au sein de votre site WordPress. Elle ajoute une page de menu d'administration dédiée et des boutons pratiques dans la barre d'outils d'administration, vous donnant un accès rapide aux actions essentielles de vidage de cache.

### Fonctionnalités

*   **Vidage de Cache Général :** Vide le cache d'objet WordPress et nettoie le cache du thème.
*   **Vidage de Cache Gutenberg :** Vide les caches spécifiques liés aux motifs, catégories, styles et fonctionnalités des blocs Gutenberg, garantissant que votre contenu basé sur des blocs est toujours à jour.
*   **Vidage des Transients :** Supprime tous les transients expirés de la base de données, optimisant ainsi les performances.
*   **Actions de Cache Extensibles :** Permet à d'autres extensions ou thèmes d'enregistrer leurs propres actions personnalisées de vidage de cache via le filtre `up_cache_control_actions`.
*   **Intégration au Menu d'Administration :** Ajoute une page dédiée "Up Cache Control" sous le menu "Outils".
*   **Intégration à la Barre d'Outils d'Administration :** Ajoute un menu "Up Cache" pratique à la barre d'outils d'administration WordPress avec des actions déroulantes.

### Installation

1.  Téléchargez le dossier `up-cache-control` dans le répertoire `/wp-content/plugins/`.
2.  Activez l'extension via le menu 'Extensions' dans WordPress.

### Utilisation

1.  **Menu d'Administration :** Accédez à "Outils" -> "Up Cache Control" pour accéder à la page principale de gestion du cache. À partir de là, vous pouvez déclencher n'importe quelle action de vidage de cache disponible en cliquant sur les boutons correspondants.

2.  **Barre d'Outils d'Administration :** Survolez le menu "Up Cache" dans la barre d'outils d'administration. Un menu déroulant apparaîtra avec des raccourcis vers chaque action de vidage de cache. Cliquer sur une action déclenchera le vidage du cache.

### Étendre avec des Actions de Cache Personnalisées

Mettre le fichier dans le plugin  /extend

Up Cache Control permet à d'autres extensions et thèmes d'ajouter leurs propres actions de vidage de cache. Pour ce faire, utilisez le filtre `up_cache_control_actions`.

**Exemple :**

```php
<?php
/**
 * Ajouter une action de vidage de cache personnalisée.
 *
 * Cet exemple doit être placé dans le fichier functions.php de votre thème ou dans une autre extension.
 */
add_filter( 'up_cache_control_actions', 'ma_fonction_personnalisee_cache_action' );

function ma_fonction_personnalisee_cache_action( $actions ) {
    $actions[] = array(
        'slug'     => 'mon-cache-personnalise', // Slug unique pour votre action
        'label'    => 'Vider Mon Cache Personnalisé', // Libellé convivial
        'callback' => 'ma_fonction_personnalisee_vider_cache', // Fonction de rappel
    );
    return $actions;
}

/**
 * Fonction de rappel pour vider votre cache personnalisé.
 */
function ma_fonction_personnalisee_vider_cache() {
    // Ajoutez votre logique de vidage de cache personnalisé ici.
    // Par exemple :
    delete_transient( 'mon_cache_personnalise_transient' );
    error_log( 'Cache personnalisé vidé !' ); // Remplacez par une journalisation réelle
}
?>
```

**Explication :**

*   **`up_cache_control_actions` :** Le filtre utilisé pour ajouter votre action personnalisée.
*   **`slug` :** Un identifiant unique pour votre action. Il doit être unique par rapport aux autres actions.
*   **`label` :** Le texte qui sera affiché dans le menu d'administration et la barre d'outils.
*   **`callback` :** La fonction qui sera exécutée lorsque l'action sera déclenchée.  C'est ici que vous mettez votre code de vidage de cache.

### Développeur

[Votre Nom]
[Votre Site Web/Profil

### Licence

GPL2 ou ultérieure