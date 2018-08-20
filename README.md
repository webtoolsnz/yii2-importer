Install
--

`composer require webtoolsnz/yii2-importer`

config/console.php
```php
[
    'bootstrap' => ['importer'],
    'controllerMap' => [
        'importer' => [
            'class' => 'backend\commands\ImporterController',
        ],
    ],
    'modules' => [
        'importer' => [
            'class' => 'webtoolsnz\importer\Module',
        ],
    ],
]
```

config/web.php
```php
[
    'bootstrap' => ['importer'],
    'modules' => [
        'importer' => [
            'class' => 'webtoolsnz\importer\Module',
        ],
    ],
]
```

controllers/ImportController.php
```php
<?php
class ImportController extends \webtoolsnz\importer\ImporterController
{
    
}
```

`cp vendor/webtoolsnz/yii2-importer/src/migrations/* migrations/`
`./yii migrate`
