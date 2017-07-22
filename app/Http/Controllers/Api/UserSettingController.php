<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\UserSetting;
use Auth;
use Gate;
use Illuminate\Http\Request;

/**
 * Class UserSettingController
 * @package App\Http\Controllers
 */
class UserSettingController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $us = UserSetting::find($id);
        if (is_null($us)) {
            return $this->respondNotFound('UserSetting not found');
        }

        if (Gate::denies('api-write:users_all') && $us->user_id != Auth::user()->id) {
            return $this->respondUnauthorized();
        }


        return $this->setStatusCode(200)->respondWithData(
            $this->user_settingTransformer->transform(
                $us->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:users_all') && $request->input('user_id') != Auth::user()->id) {
            return $this->respondUnauthorized();
        }

        $user = User::find($request->input('user_id'));
        if (is_null($user)) {
            return $this->setStatusCode(422)->respondWithError('User not found');
        }

        if (!is_null($user->settingById($id))) {
            $user->deleteSettingById($id);
        }

        return $this->setStatusCode(200)->respondWithData(
            [],
            [
                'redirect' => [
                    'uri'   => url('users/' . $user->id . '/edit'),
                    'delay' => 1000
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $this->update($request);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if (Gate::denies('api-write:users_all') && $request->input('id') != Auth::user()->id && Gate::denies('api-write:user_self')) {
            return $this->respondUnauthorized();
        }

        $user = User::find($request->input('user_id'));
        if (is_null($user)) {
            return $this->setStatusCode(422)->respondWithError('User not found');
        }

        if (!is_null($user->setting($request->input('name'))))
            $user_setting = $user->settings()->where('name', $request->input('name'))->first();
        else
            $user_setting = UserSetting::create([
                'user_id'   => $request->input('user_id'),
                'name'      => $request->input('name')
            ]);

        $user_setting->value = $request->input('value');
        $user_setting->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $user_setting->id
            ]
        );

    }

}
