- Avoir installé php 8 avec ses dépendances
- Installer composer
- Lancer un composer install après avoir récupéré le projet ```composer install```

## Résolution du problème au lancement des tests

La commande classique `symfony php bin/phpunit` n'est pas reconnue. Pour lancer les tests, j'utilise :

```bash
$ symfony php vendor/bin/phpunit
```

