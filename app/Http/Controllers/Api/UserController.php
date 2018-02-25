<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\UserSettingTransformer;
use App\User;
use App\UserAbility;
use App\UserSetting;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Laravel\Passport\Token;


/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends ApiController
{

    /**
     * UserController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list_admin')) {
            return $this->respondUnauthorized();
        }

        $users = User::query();
        $users = $this->filter($request, $users);

        return $this->respondTransformedAndPaginated(
            $request,
            $users
        );
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:user_self') && Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var User $user
         */
        $user = User::find($id);
        if (is_null($user)) {
            return $this->respondNotFound('User not found');
        }

        /*
         * Make sure non-admin users can only edit themselves
         */
        if (Gate::denies('admin') && $user->id != Auth::user()->id) {
            return $this->respondUnauthorized();
        }

        $user->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('user/' . $user->id)
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var User $user
         */
        $user = User::where('name', $request->input('name'))->get()->first();
        if (!is_null($user)) {
            return $this->setStatusCode(422)->respondWithError(trans('errors.username_taken'));
        }

        /**
         * @var User $user
         */
        $user = User::where('email', $request->input('email'))->get()->first();
        if (!is_null($user)) {
            return $this->setStatusCode(422)->respondWithError(trans('errors.email_taken'));
        }

        if ($request->filled('password') && $request->filled('password_2')) {
            if ($request->input('password') !== $request->input('password_2')) {
                return $this->setStatusCode(422)->respondWithError(trans('errors.passwords_do_not_match'));
            }

            $password = bcrypt($request->input('password'));
        }
        else {
            return $this->setStatusCode(422)->respondWithError(trans('errors.no_password'));
        }

        /**
         * @var User $user
         */
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $password
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    => $user->id
            ],
            [
                'redirect' => [
                    'uri'   => url('users/' . $user->id . '/edit')
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:user_self') && Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var User $user
         */
        $user = User::find($id);
        if (is_null($user)) {
            return $this->respondNotFound('User not found');
        }

        /*
         * Make sure non-admin users can only edit themselves
         */
        if (Gate::denies('admin') && $user->id != Auth::user()->id) {
            return $this->respondUnauthorized();
        }

        if (Gate::allows('admin')) {
            if ($request->filled('name')) {
                $user->name = $request->input('name');
            }

            if ($request->filled('email')) {
                $user->email = $request->input('email');
            }

            if ($request->filled('password') && $request->filled('password_2')) {
                if ($request->input('password') !== $request->input('password_2')) {
                    return $this->setStatusCode(422)->respondWithError(trans('errors.passwords_do_not_match'));
                }

                $user->password = bcrypt($request->input('password'));
            }

            if ($request->filled('abilities')) {
                foreach ($user->abilities as $a) {
                    if (!in_array($a->name, array_values($request->input('abilities')))) {
                        $a->delete();
                    }
                }

                foreach ($request->input('abilities') as $a) {
                    if (!$user->ability($a)) {
                        UserAbility::create([
                            'user_id' => $user->id,
                            'name' => $a
                        ]);
                    }
                }
            }

        }

        $this->updateModelProperties($user, $request, [
            'locale' => 'language', 'timezone'
        ]);
        
        if ($request->filled('night_starts_at')) {
            $user->setSetting('night_starts_at', $request->input('night_starts_at'));
        }

        if ($request->filled('night_ends_at')) {
            $user->setSetting('night_ends_at', $request->input('night_ends_at'));
        }

        if ($request->filled('notification_type')) {
            $user->setSetting('notification_type', $request->input('notification_type'));
        }

        if ($request->filled('notifications_enabled')) {
            $user->setSetting('notifications_enabled', $request->input('notifications_enabled'));
        }

        if ($request->filled('notifications_controlunits_enabled')) {
            $user->setSetting('notifications_controlunits_enabled', $request->input('notifications_controlunits_enabled'));
        }

        if ($request->filled('notifications_terraria_enabled')) {
            $user->setSetting('notifications_logical_sensors_enabled', $request->input('notifications_terraria_enabled'));
            $user->setSetting('notifications_generic_components_enabled', $request->input('notifications_terraria_enabled'));
        }

        if ($request->filled('notifications_daily_enabled')) {
            $user->setSetting('notifications_daily_enabled', $request->input('notifications_daily_enabled'));
        }

        if ($request->filled('auto_nightmode_enabled')) {
            $user->setSetting('auto_nightmode_enabled', $request->input('auto_nightmode_enabled'));
        }

        if ($request->filled('permanent_nightmode_enabled')) {
            $user->setSetting('permanent_nightmode_enabled', $request->input('permanent_nightmode_enabled'));
        }

        $user->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('users/' . $user->id . '/edit')
            ]
        ]);

    }

    /**
     * @param $user_id
     * @param $setting_name
     * @return \Illuminate\Http\JsonResponse
     */
    public function setting($user_id, $setting_name)
    {
        $us = UserSetting::where('user_id', $user_id)->where('name', $setting_name)->first();
        if (is_null($us))
            return $this->respondNotFound('UserSetting not found');

        $user_setting_controller = new UserSettingController(new UserSettingTransformer());
        return $user_setting_controller->show($us->id);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_personal_access_token(Request $request, $id)
    {
        if (Gate::denies('api-write:user_self') && Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        $user = User::find($id);
        if (is_null($user)) {
            return $this->respondNotFound('User not found');
        }

        /*
         * Make sure non-admin users can only edit themselves
         */
        if (Gate::denies('admin') && $user->id != Auth::user()->id) {
            return $this->respondUnauthorized();
        }

        $token = $user->createToken($request->input('name'))->accessToken;

        return $this->respondWithData([
            'token' => $token
        ], [
            'redirect' => [
                'uri' => url('users/' . $user->id . '/personal_access_tokens')
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $token_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_personal_access_token(Request $request, $id, $token_id)
    {
        if (Gate::denies('api-write:user_self') && Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        $user = User::find($id);
        if (is_null($user)) {
            return $this->respondNotFound('User not found');
        }

        /*
         * Make sure non-admin users can only edit themselves
         */
        if (Gate::denies('admin') && $user->id != Auth::user()->id) {
            return $this->respondUnauthorized();
        }

        /*
         * TODO:
         * Should work but doesn't :(
         */
        //$request->user()->tokens()->where('id', $token_id)->get()->revoke();

        Token::find($token_id)->revoke();

        return $this->respondWithData([], [
            'redirect' => [
                'uri' => url('users/' . $user->id . '/edit')
            ]
        ]);
    }

}
