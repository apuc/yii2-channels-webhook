<?php


namespace apuc\channels_webhook\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\HttpException;

class WebHookBehavior extends Behavior
{
    /**
     * @var string|null url по которому будет отправляться запрос
     */
    public $url = null;

    /**
     * @var array аттрибуты, которые будут отправляться вместе с запросом, по умолчанию отправляет все аттрибуты
     */
    public $attributes = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'throwHook',
            ActiveRecord::EVENT_AFTER_UPDATE => 'throwHook',
        ];
    }

    public function throwHook()
    {
        throw new HttpException(434, $this->url);
    }
}