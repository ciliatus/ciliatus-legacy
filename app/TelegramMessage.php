<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramMessage
 *
 * @package App
 * @property string $id
 * @property string $user_id
 * @property string $type
 * @property string $state
 * @property string $content
 * @property string $response_to
 * @property \Carbon\Carbon $send_after
 * @property \Carbon\Carbon $sent_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereResponseTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereSendAfter($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereUserId($value)
 * @mixin \Eloquent
 */
class TelegramMessage extends Message
{
    use Traits\Uuids;

    /**
     * @var string
     */
    private $url;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'content'];

    /**
     * TelegramMessage constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->url = 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN');
    }

    /**
     * @param array $attributes
     * @return TelegramMessage
     */
    public static function create(array $attributes = [])
    {
        $telegram_message = new TelegramMessage($attributes);
        $telegram_message->save();
        return $telegram_message;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'TelegramMessage');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    /**
     * @param null $chat_id
     * @return bool
     */
    public function send($chat_id = null)
    {
        $this->type = 'out';

        if (strlen($this->content) < 1) {
            \Log::error('TelegramMessage content missing');
            return false;
        }

        if (is_null($this->user)) {
            \Log::error('TelegramMessage not associated with a user.');
            return false;
        }

        $chat_id = $this->user->setting('notifications_telegram_chat_id');

        if (is_null($chat_id)) {
            \Log::error('TelegramMessage chat_id missing');
            return false;
        }


        $client = new Client();
        try {
            $params = [
                'chat_id'   =>  $chat_id,
                'text'      =>  $this->content,
            ];

            if (!is_null($this->response_to)) {
                $params['reply_to_message_id'] = $this->response_to;
            }

            $res = $client->request('POST', $this->url . '/sendMessage', [
                'form_params'   => $params
            ]);

            $this->state = 'sent';
            $this->sent_at = Carbon::now();
            $this->save();
        }
        catch (\GuzzleHttp\Exception\ClientException $ex) {
            $this->state = 'send_failed';
            $this->sent_at = Carbon::now();
            $this->save();
            \Log::error($ex->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function icon()
    {
        // TODO: Implement icon() method.
    }

    /**
     *
     */
    public function url()
    {
        // TODO: Implement url() method.
    }
}
