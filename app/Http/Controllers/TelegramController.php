<?php

namespace App\Http\Controllers;

use App\UserSetting;
use Gate;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Mockery\CountValidator\Exception;
use Telegram\Bot\HttpClients\GuzzleHttpClient;


/**
 * Class ValveController
 * @package App\Http\Controllers
 */
class TelegramController extends PublicApiController
{

    /**
     * @var string
     */
    private $url;

    /**
     * TelegramController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->url  = 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        \Log::debug($request->all());
        try {
            $request_arr = $request->all();

            $update_id = $request_arr['update_id'];
            $chat_id = $request_arr['message']['from']['id'];
            $first_name = $request_arr['message']['from']['first_name'];
            $command = explode(' ', $request_arr['message']['text']);

            $user_setting = UserSetting::where('name', 'notifications_telegram_chat_id')
                                        ->where('value', $chat_id)
                                        ->first();

            $user = null;
            if (!is_null($user_setting)) {
                $user = $user_setting->user;
            }

            switch ($command[0]) {
                case '/start':
                    if (is_null($user)) {
                        $this->send($chat_id, 'Hello ' . $first_name . ', please enter your verification code.', $update_id);
                    }
                    else {
                        $this->send($chat_id, 'You are already verified.');
                    }
                    break;

                default:
                    if (is_null($user)) {
                        $verifying_user_setting = UserSetting::where('name', 'notifications_telegram_verification_code')
                            ->where('value', $command[0])
                            ->first();

                        if (is_null($verifying_user_setting)) {
                            $this->send($chat_id, 'Verification code is invalid.', $update_id);
                        } else {
                            $verifying_user_setting->user->setSetting('notifications_telegram_chat_id', $chat_id);
                            $verifying_user_setting->user->deleteSetting('notifications_telegram_verification_code');
                            $this->send($chat_id, 'Verification code accepted. Have fun.', $update_id);
                        }
                    }
                    else {

                    }
            }

        }
        Catch (Exception $ex) {
            \Log::error($ex->getMessage());
        }

        return $this->respondWithData();
    }

    /**
     * @param $chat_id
     * @param $text
     * @param null $reply_id
     * @return bool
     */
    public function send($chat_id, $text = 'No Text', $reply_id = null)
    {
        if (strlen($text) < 1)
            return false;

        $client = new Client();
        try {
            $res = $client->request('POST', $this->url . '/sendMessage', [
                'form_params'   => [
                    'chat_id'   =>  $chat_id,
                    'text'      =>  $text
                ]
            ]);
        }
        catch (\GuzzleHttp\Exception\ClientException $ex) {
            \Log::error($ex->getMessage());
            return false;
        }

        return $res->getStatusCode() == 200;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerChatId(Request $request)
    {

        if (Gate::denies('api-write:user_telegram')) {
            return $this->respondUnauthorized('Unauthrorized');
        }

        if (!$this->checkInput(['chat_id', 'phone_no'], $request)) {
            return $this->setStatusCode(422)
                        ->respondWithError('Missing parameters. Required: chat_id, phone_no');
        }

        $user_setting = UserSetting::where('name', 'notifications_telegram_phone_no')
                                    ->where('value', $request->input('phone_no'))
                                    ->first();

        if (is_null($user_setting)) {
            return $this->respondNotFound('Phone number not found');
        }

        $user = $user_setting->user;
        $user->setSetting('notifications_telegram_chat_id', $request->input('chat_id'));
        $user->save();

        return $this->respondWithData();
    }

}
