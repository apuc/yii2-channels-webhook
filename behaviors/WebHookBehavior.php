<?php


namespace apuc\channels_webhook\behaviors;


use yii\base\Behavior;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\widgets\ActiveForm;

class WebHookBehavior extends Behavior
{
    /**
     * @var string url по которому будет отправляться запрос
     */
    public $url = 'https://webhooks.mychannels.gq';

    /**
     * @var array аттрибуты, которые будут отправляться вместе с запросом, по умолчанию отправляет все аттрибуты
     */
    public $attributes = [];

    /**
     * @var array связанные сущности, которые будут отправляться вместе с моделью
     */
    public $relations = [];

    /**
     * @var bool должен ли веб хук срабатывать при создании новой записи
     */
    public $onInsert = true;

    /**
     * @var bool должен ли веб хук срабатывать при изменении записи
     */
    public $onUpdate = true;

    public function events()
    {
        $events=[];
        if($this->onInsert)
            $events[ActiveRecord::EVENT_AFTER_INSERT] = 'throwHook';
        if($this->onUpdate)
            $events[ActiveRecord::EVENT_AFTER_UPDATE] = 'throwHook';
        return $events;
    }

    public function throwHook()
    {
        if($this->attributes === []){
            $attributes = $this->owner->attributes;
        } else {
            $attributes=[];
            foreach ($this->attributes as $attribute) {
                $attributes[$attribute] = $this->owner->attributes[$attribute];
            }
        }
        foreach ($this->relations as $relation){
            $data = $this->owner->$relation;
            if(!is_array($data))
                $attributes[$relation]=$data->attributes;
            else {
                foreach ($data as $d) {
                    $attributes[$relation][]=$d->attributes;
                }
            }
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($attributes));
        $response = curl_exec($curl);
        curl_close($curl);
	return ['attributes' => $attributes, 'response' => $response];
    }
}


