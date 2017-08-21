<?php

namespace App;

/**
 * Class Message
 * @package App
 */
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class Message
 * @package App
 */
class Message extends CiliatusModel
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
     * @throws \InvalidArgumentException
     * @return CiliatusModel|Message|TelegramMessage
     */
    public static function create(array $attributes = [])
    {
        if (array_key_exists('type', $attributes)) {
            switch ($attributes['type']) {
                case 'Telegram':
                    unset($attributes['type']);
                    return TelegramMessage::create($attributes);
                default:
                    throw new \InvalidArgumentException('Invalid type attribute');
            }
        }
        else {
            \Log::debug('in4');
            throw new \InvalidArgumentException('Missing attribute: type');
        }
    }

    /**
     * Like find, but returns the child
     * class by evaluating the type property
     *
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Message');
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url() {
        return url('messages/' . $this->id);
    }

    /**
     * @return string
     */
    public function icon() {
        return '';
    }

    /**
     * @throws \Exception
     */
    public function send() {
        throw new \Exception("Not implemented. Make sure to define send method in child class.");
    }
}
