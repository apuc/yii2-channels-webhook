channels-webhook
================
Extention, that allows you to throw a webhook after saving model

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist apuc/yii2-channels-webhook "*"
```

or add

```
"apuc/yii2-channels-webhook": "*"
```

to the require section of your `composer.json` file.

Usage example

```
"websocket" => [
    "class" => WebHookBehavior::className(),
    "url" => "https://example.com"
    "onUpdate" => false
]
```

You can also add module for testing

```
'module' => [
    'test' => [
        'class' => 'apuc\channels_webhook\modules\test\Test',
    ],
]
```
