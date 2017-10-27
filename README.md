Translate DB Yii2
===========================
Translate DB tables and columns for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist matthew-p/yii2-translate-db "*"
```

or add

```
"matthew-p/yii2-translate-db": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by:

In console main.php add:
```php
return [
    ...
    'controllerMap' => [
        ...
        'message' => [
            'class'            => MPMessageController::class,
            'configFile'       => '@common/messages/config.php', // Set path to message config file
            'storedDbMessages' => '@common/db_messages.php',     // File where temporary stored data from db. This file must not be blocked (except) in messages config.php
            'tablesMessages'   => [
                ARModel::class  => ['title', 'title_for'], // ActiveRecord model => [column1, column2 ...]
                ARModel2::class => ['title'],
            ]  
        ],
        ...
    ]
    ...
];
```

And run in console:
```bash
php yii message
```