<?php

namespace Apps\Http\Controllers\Store;

use Apps\user;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

use Apps\Http\Requests\Store\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Apps\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, user $user) {

        if ($user->userable_type != 'Apps\Customer') {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'usuario' => ['La actualización de este típo de usuario no esta permitido.'],
                ],
            ], 422);
        }

        $user->update([
            'email' => $request->get('email'),
            'active' => $request->get('active'),
        ]);

        return response()->json([
            'data' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        //
    }
}
