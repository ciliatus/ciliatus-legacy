<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Property;
use Illuminate\Http\Request;

/**
 * Class SetupController
 * @package App\Http\Controllers\Web
 */
class SetupController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function start()
    {
        $setup_complete_property = Property::where('type', 'SetupCompleted')->get()->first();
        if (!is_null($setup_complete_property)) {
            return view ('setup.err_completed');
        }

        return view('setup.start');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function step(Request $request, $id)
    {
        $this->setLocale();

        switch ($id) {
            case 1:
                return view('setup.step_1');
            case 2:
                return view('setup.step_2');
            default:
                return view('setup.start');
        }

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
