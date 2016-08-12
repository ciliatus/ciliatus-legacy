<?php

namespace App\Http\Controllers;

use App\TelegramMessage;
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
     * TelegramController constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
            $message_id = $request_arr['message']['message_id'];
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

            $response_message = TelegramMessage::create();
            if (!is_null($user)) {
                $response_message->user_id = $user->id;
            }
            $response_message->response_to = $message_id;

            switch ($command[0]) {
                case '/start':
                    if (is_null($user)) {
                        $response_message->content = 'Hello ' . $first_name . ', please enter your verification code.';
                        $response_message->send($chat_id);
                    }
                    else {
                        $response_message->content = 'You are already verified.';
                        $response_message->send();
                    }
                    break;

                default:
                    if (is_null($user)) {
                        $verifying_user_setting = UserSetting::where('name', 'notifications_telegram_verification_code')
                            ->where('value', $command[0])
                            ->first();

                        if (is_null($verifying_user_setting)) {
                            $response_message->content = 'Verification code is invalid.';
                            $response_message->send($chat_id);
                        }
                        else {
                            $verifying_user_setting->user->setSetting('notifications_telegram_chat_id', $chat_id);
                            $verifying_user_setting->user->deleteSetting('notifications_telegram_verification_code');
                            $response_message->content = 'Verification code accepted. Have fun.';
                            $response_message->send($chat_id);
                        }
                    }
                    else {
                        $response_message->content = 'Nothing to do ...';
                        $response_message->send($chat_id);
                    }
            }

        }
        Catch (Exception $ex) {
            \Log::error($ex->getMessage());
        }

        return $this->respondWithData();
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
