<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramMessage
 * @package App
 */
class TelegramMessage extends Message
{
    use Traits\Uuids;

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

    protected $fillable = ['user_id', 'content'];

    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
        $this->url = 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
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


    public function send($chat_id = null)
    {
        if (strlen($this->content) < 1) {
            \Log::error('TelegramMessage content missing');
            return false;
        }

        if (!is_null($this->user)) {
            $chat_id = $this->user->setting('notifications_telegram_chat_id');
        }

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
        }
        catch (\GuzzleHttp\Exception\ClientException $ex) {
            \Log::error($ex->getMessage());
            return false;
        }

        return true;
    }
}
