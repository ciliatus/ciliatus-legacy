<?php

namespace App\Http\Controllers\Api;

use App\Property;
use App\User;
use Illuminate\Http\Request;

/**
 * Class SetupController
 * @package App\Http\Controllers\Api
 */
class SetupController extends ApiController
{

    /**
     * SetupController constructor.
     */
    public function __construct(Request $request)
    {
        // empty to disable auth.basic
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function step(Request $request, $id)
    {
        $this->setLocale();

        switch ($id) {
            case 0:
                return $this->step_0($request);
            case 1:
                return $this->step_1($request);
            default:
                return $this->respondNotFound();
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function step_0(Request $request)
    {

        Property::create([
            'belongsTo_type' => 'System',
            'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
            'type' => 'SetupCompleted',
            'name' => 'Step0'
        ]);

        Property::create([
            'belongsTo_type' => 'System',
            'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
            'type' => 'SetupConfiguration',
            'name' => 'language',
            'value' => $request->input('language')
        ]);

        return $this->respondWithData([]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function step_1(Request $request)
    {
        $user = User::where('name', $request->input('name'))->get()->first();
        if (!is_null($user)) {
            return $this->setStatusCode(422)->respondWithError(trans('errors.username_taken'));
        }

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

        $locale_prop = Property::where('type', 'SetupConfiguration')->where('name', 'language')->get()->first();
        if (is_null($locale_prop)) {
            $locale = 'en';
        }
        else {
            $locale = $locale_prop->value;
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $password,
            'locale' => $locale
        ]);

        $user->grantFullAbilities();

        /**
         * Create presets
         */
        $biography_entry_categories = [
            'abnormalities' =>  'live_help',
            'terrarium'     =>  'video_label',
            'veterinarian'  =>  'local_hospital'
        ];
        foreach ($biography_entry_categories as $name=>$icon) {
            Property::create([
                'belongsTo_type' => 'System',
                'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
                'type' => 'BiographyEntryCategoryType',
                'name' => $name,
                'value' => $icon
            ]);
        }

        $food_types = ['repashy', 'pangea', 'fruit_mix'];
        foreach ($food_types as $name) {
            Property::create([
                'belongsTo_type' => 'System',
                'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
                'type' => 'AnimalFeedingType',
                'name' => $name,
                'value' => $name
            ]);
        }

        return $this->respondWithData([]);
    }

    /**
     *
     */
    private function setLocale()
    {
        $p = Property::where('type', 'SetupConfiguration')->where('name', 'language')->get()->first();
        if (is_null($p)) {
            app()->setLocale('en');
        }
        else {
            app()->setLocale($p->value);
        }
    }

}
