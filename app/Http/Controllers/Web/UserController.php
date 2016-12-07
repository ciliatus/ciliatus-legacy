<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Token;
use Gate;
use Auth;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Controller
{

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('admin')) {
            return view('errors.401');
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return view('errors.404');
        }

        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return view('errors.404');
        }


        return view('users.edit', [
            'user'     => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return view('errors.404');
        }

        return view('users.delete', [
            'user'     => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function setup_Telegram()
    {
        $user = Auth::user();

        $user->deleteSetting('notifications_telegram_chat_id');
        $user->setSetting('notifications_telegram_verification_code', Token::generate(6));

        return view('users.setup_telegram', [
            'user'  =>  $user,
            'token' =>  $user->setting('notifications_telegram_verification_code')
        ]);
    }
}
