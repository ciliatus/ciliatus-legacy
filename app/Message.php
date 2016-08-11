<?php

namespace App;

/**
 * Class Message
 * @package App
 */
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class Message
 * @package App
 */
abstract class Message extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'type'];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'send_after', 'sent_at'];

    /**
     * Returns a child class if
     * method is defined in $attributes
     *
     * @param array $attributes
     * @return CiliatusModel|Message|TelegramMessage
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);

        if (!is_null($new->type))
            return self::findSpecific($new->id);

        return $new;
    }

    /**
     * @param $id
     * @return null
     */
    public static function findSpecific($id)
    {
        $model = self::find($id);
        if (is_null($model))
            return null;

        switch ($model->type) {
            case 'Telegram':
                return TelegramMessage::find($id);
            default:
                return $model;
        }
    }

    /**
     * @return mixed
     */
    abstract public function send();
}
