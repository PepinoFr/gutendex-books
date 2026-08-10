# Gutendex Books

Plugin WordPress permettant d'afficher et rechercher des livres depuis l'API Gutendex.

## Installation

1. Copier le dossier `gutendex-books` dans `wp-content/plugins/`.
2. Activer le plugin depuis **Extensions** dans WordPress.
3. Ajouter le shortcode suivant dans une page :

```text
[books_list]
```

## Fonctionnalités

* Recherche par titre
* Filtre par langue
* Pagination
* Chargement des résultats en AJAX
* Mise en cache des réponses API
* Configuration de la durée du cache
* Traduction française
* Commande WP-CLI pour renouveler le cache

## Choix techniques

* **PHP / WordPress** pour le plugin
* **API Gutendex** pour les données
* **Transients WordPress** pour le cache
* **AJAX** pour la recherche et la pagination sans rechargement
* **JavaScript** pour les interactions utilisateur
* **WordPress i18n** pour les traductions
* **WP-CLI** pour la gestion du cache

## Choix du mode d'intégration

J'ai choisi d'utiliser un **shortcode WordPress** :

```text
[books_list]
```

Ce choix permet d'intégrer facilement la liste des livres dans n'importe quelle page ou article WordPress, sans créer automatiquement de page supplémentaire.

Le shortcode génère le formulaire de recherche, les résultats et la pagination. Les recherches et changements de page sont ensuite gérés en AJAX afin d'éviter le rechargement complet de la page.


## Limitations

* Les résultats dépendent de la disponibilité de l'API Gutendex.
* Un délai de réponse de l'API peut ralentir l'affichage.

## Temps passé

Environ 3 heures, incluant le développement, les tests et les corrections.
