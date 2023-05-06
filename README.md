- Avoir installé php 8 avec ses dépendances
- Installer composer
- Lancer un composer install après avoir récupéré le projet ```composer install```

## Résolution du problème au lancement des tests

La commande classique `symfony php bin/phpunit` n'est pas reconnue. Pour lancer les tests, j'utilise :

```bash
$ symfony php vendor/bin/phpunit
```

## Procédure pour mocker différentes parties d'une classe

NOTE : on trouvera la source des exemples suivants dans un autre projet sur le laptop :

C:\Users\bulam\Documents\test-php-unit-openclassrooms\testez-unitairement-votre-application-symfony-php

Dans les classes complexes, afin de contrôler au mieux les résultats, il est nécessaire de s'assurer des résultats de certaines parties du code.
Par exemple :

```php
        // pour mocker les dépendances d'un constructeur
        $response = $this
            ->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        // pour mocker le retour d'une méthode quelconque
        $client
            ->method('get')
            ->willReturn($response);
```