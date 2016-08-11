<?php

namespace App\Http\Controllers;

use App\Controlunit;
use App\Http\Transformers\UserSettingTransformer;
use App\Pump;
use App\Http\Transformers\UserTransformer;
use App\User;
use App\Terrarium;
use App\UserAbility;
use App\UserSetting;
use Auth;
use Gate;
use Illuminate\Http\Request;


/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends ApiController
{
    /**
     * @var UserTransformer
     */
    protected $userTransformer;

    /**
     * UserController constructor.
     * @param UserTransformer $_userTransformer
     */
    public function __construct(UserTransformer $_userTransformer)
    {
        parent::__construct();
        $this->userTransformer = $_userTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list_admin')) {
            return $this->respondUnauthorized();
        }

        $users = User::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination(
            $this->userTransformer->transformCollection(
                $users->toArray()['data']
            ),
            $users
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $user = User::find($id);

        if (!$user) {
            return $this->respondNotFound('User not found');
        }
        return $this->setStatusCode(200)->respondWithData(
            $this->userTransformer->transform(
                $user->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:user_self') && Gate::denies('api-write:user_all')) {
            return $this->respondUnauthorized();
        }

        $user = User::find($id);
        if (is_null($user)) {
            return $this->respondNotFound('User not found');
        }

        /*
         * Make sure non-admin users can only edit themselves
         */
        if (Gate::denies('api-write:user_all') && $user->id != Auth::user()->id) {
            return $this->respondUnauthorized();
        }

        $user->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('user/' . $user->id),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:user_all')) {
            return $this->respondUnauthorized();
        }

        $user = User::create();
        $user->name = $request->input('name');
        $user->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    => $user->id
            ],
            [
                'redirect' => [
                    'uri'   => url('users/' . $user->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        if (Gate::denies('api-write:user_self') && Gate::denies('api-write:user_all')) {
            return $this->respondUnauthorized();
        }

        $user = User::find($request->input('id'));
        if (is_null($user)) {
            return $this->respondNotFound('User not found');
        }

        /*
         * Make sure non-admin users can only edit themselves
         */
        if (Gate::denies('api-write:user_all') && $user->id != Auth::user()->id) {
            return $this->respondUnauthorized();
        }

        if (Gate::allows('api-write:user_all')) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            foreach ($user->abilities as $a) {
                if (!in_array($a->name, array_values($request->input('abilities')))) {
                    $a->delete();
                }
            }
            foreach ($request->input('abilities') as $a) {
                if (!$user->ability($a)) {
                    $new_ability = UserAbility::create(['user_id', $user->id]);
                    $new_ability->name = $a;
                    $new_ability->save();
                }
            }
        }

        $user->locale = $request->input('language');
        $user->timezone = $request->input('timezone');
        $user->setSetting('notification_type', $request->input('notification_type'));
        $user->setSetting('notifications_enabled', $request->input('notifications_enabled'));
        $user->setSetting('auto_nightmode_enabled', $request->input('auto_nightmode_enabled'));
        $user->setSetting('permanent_nightmode_enabled', $request->input('permanent_nightmode_enabled'));

        $user->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('users/' . $user->id . '/edit'),
                'delay' => 1000
            ]
        ]);

    }

    public function setting($user_id, $setting_name)
    {
        $us = UserSetting::where('user_id', $user_id)->where('name', $setting_name)->first();
        if (is_null($us))
            return $this->respondNotFound('UserSetting not found');

        $user_setting_controller = new UserSettingController(new UserSettingTransformer());
        return $user_setting_controller->show($us->id);

    }

}
