<?php

namespace Apps\Http\Controllers;

use Illuminate\Http\Request;
use Apps\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Apps\Collaborator;
use Apps\User;
use Apps\Rol;
use Apps\App;

use Validator;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        switch ($request->get('typeAuth')) {
            case 'user/password':

                $this->validate($request, [
                    'credentials.email' => 'required|email|exists:users,email',
                    'credentials.password' => 'required',
                ], [], ['credentials.email' => 'correo eletrónico o contraseña']);

                $credentials = collect($request->get('credentials'));

                // if (User::where([['email', $credentials->get('email')], ['active', true]])->get()->isEmpty()) {
                //     return response()->json([
                //         'success' => false,
                //         'message' => 'Wrong credentials.',
                //         'errors' => [
                //             'user' => ['El usuario se encuentra deshabilitar.'],
                //         ]
                //     ], 422);  
                // }

                $token = JWTAuth::attempt($credentials->only(['email', 'password'])->toArray());

                if ($token) {
                    $user = Auth::user();

                    switch ($user->acount_type_id) {
                        case 1:
                            $user = User::with('apps', 'collaborator:id,name', 'roles', 'support_staff')->find($user->id);

                            $collaborator = Collaborator::withTrashed()->select(['id', 'name'])->find($user->userable_id);

                            $user->name = $collaborator->name;

                            $user->type = 'admin';

                            return response()->json([
                                'success' => true,
                                'data' => [
                                    'token' => $token,
                                    'user' => collect($user),
                                    'typeAuth' => 'user/password',
                                    'collaborator' => $collaborator,
                                    'redirect' => $user->apps->first()->url,                                    
                                ],
                            ], 200, [], JSON_NUMERIC_CHECK);
                            break;
                        case 2:
                            // Cliente
                            return response()->json([
                                'success' => true,
                                'data' => [
                                    'user' => $user->only('email_verified_at'),
                                    'token' => $token,
                                    'redirect' => $user->apps->first()->url,
                                ],
                            ], 200, [], JSON_NUMERIC_CHECK);
                            break;                            
                        default:
                            return response()->json([
                                'success' => false,
                                'message' => 'Wrong credentials.',
                                'errors' => [
                                    'acount_type' => ['Típo de cuenta desconocida.'],
                                ]
                            ], 422);  
                            break;
                    }                
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Wrong credentials.',
                    'errors' => [
                        'email' => ['No se ha encontrado un usuario con estas credenciales.'],
                        'password' => ['No se ha encontrado un usuario con estas credenciales.'],
                    ]
                ], 422);                

                break;

            case 'folio':
                $this->validate($request, [
                    'credentials.folio' => 'required|string|min:1|max:8|exists:nom.collaborators,folio',
                ], [], ['credentials.folio' => '"Folio"']);

                $credentials = collect($request->get('credentials'));

                $collaborator = Collaborator::where('folio', $credentials['folio'])
                ->get()
                ->first();

                $apps = App::find(1);

                $collaborator->apps = [$apps];
                
                $user = $collaborator;

                $user->type = 'user';

                $user->roles = [Rol::find(5)];

                return response()->json([
                    'success' => true,
                    'data' => [
                        'token' => null,
                        'user' => $user,
                        'typeAuth' => 'folio',
                        'redirect' => $apps->url,
                        'collaborator' => $collaborator,
                    ],
                ], 200, [], JSON_NUMERIC_CHECK);                

                break;
            default:
                break;
        }
    }

    // public function loginV3($request){
    //     $validator = Validator::make($request->all(), [
    //         'typeAuth' => 'required',
    //         'credentials' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => array_merge($validator->errors()->get("typeAuth"), $validator->errors()->get("credentials"))
    //         ], 422);
    //     }

    //     switch ($request->get('typeAuth')) {
    //         case 'user/password':

    //             $validator = Validator::make($request->get('credentials'), [
    //                 'email' => 'required|email|exists:users,email',
    //                 'password' => 'required',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Wrong validation.',
    //                     'errors' => $validator->errors()
    //                 ], 422);
    //             }

    //             $credentials = collect($request->get('credentials'));
    //             $credentials = $credentials->only(['email', 'password'])->toArray();

    //             $token = JWTAuth::attempt($credentials);

    //             if ($token) {
    //                 $user = User::with('apps', 'collaborator', 'roles', 'support_staff')
    //                         ->where('email', $credentials['email'])
    //                         ->get()
    //                         ->first();

    //                 $user->type = 'admin';

    //                 $user->defaultAppUrl = $user->apps->first()->url;

    //                 return response()->json([
    //                     'success' => true,
    //                     'data' => [
    //                         'session' => [
    //                             'token' => $token,
    //                             'typeAuth' => 'user/password',
    //                             'user' => collect($user)->except("config"),
    //                             'collaborator' => $user->collaborator,
    //                         ],
    //                     ],
    //                 ], 200, [], JSON_NUMERIC_CHECK);           
    //             }

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Wrong credentials.',
    //                 'errors' => [
    //                     'email' => ['No se ha encontrado un usuario con estas credenciales.'],
    //                     'password' => ['No se ha encontrado un usuario con estas credenciales.'],
    //                 ]
    //             ], 401);   

    //         case 'folio':
    //             $validator = Validator::make($request->get('credentials'), [
    //                 'folio' => 'required|integer|exists:nom.collaborators,id',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Wrong validation.',
    //                     'errors' => $validator->errors()
    //                 ], 422);
    //             }                

    //             $collaborator = Collaborator::find($request->get('credentials'))->first();
    //             $collaborator->apps = [["id" => 1,"url" => "https://spa.pinturasacuario.mx/rh/", 'name' => 'colaboradores']];
    //             $collaborator->defaultAppUrl = "https://spa.pinturasacuario.mx/ti";

    //             $user = $collaborator;

    //             $user->type = 'user';

    //             $user->roles = [Rol::find(5)];                

    //             return response()->json([
    //                 'success' => true,
    //                 'data' => [
    //                     'session' => [
    //                         'token' => null,
    //                         'typeAuth' => 'folio',
    //                         'user' => $user,
    //                         'collaborator' => $collaborator,
    //                     ],
    //                 ],
    //             ], 200, [], JSON_NUMERIC_CHECK);

    //         default:
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Típo de autenticación desconocido.',
    //             ], 422);             
    //             break;
    //     }
    // } 

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function loginV1(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'type' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Wrong validation.',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     switch ($request->get('type')) {
    //         case 'user/password':
    //             $validator = Validator::make($request->get("credentials"), [
    //                 'email' => 'required|email',
    //                 'password' => 'required',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Wrong validation.',
    //                     'errors' => $validator->errors()
    //                 ], 422);
    //             }

    //             $credentials = $request->get('credentials');
                
    //             $token = JWTAuth::attempt($credentials);

    //             if ($token) {
    //                 return response()->json([
    //                     'success' => true,
    //                     "data" => [
    //                         'token' => $token,
    //                         'type' => $request->get('type'),
    //                         'user' => User::where('email', $credentials['email'])->get()->first()
    //                     ],
    //                 ], 200);             
    //             }

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Wrong credentials.',
    //             ], 401);   

    //             break;
    //         case 'folio':
    //             $validator = Validator::make($request->get("credentials"), [
    //                 'folio' => 'required|integer',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Wrong validation.',
    //                     'errors' => $validator->errors()
    //                 ], 422);
    //             }                

    //             $credentials = $request->get("credentials");

    //             $collaborator = Collaborator::find($credentials["folio"]);

    //             if (!empty($collaborator)) {
    //                 return response()->json([
    //                     'success' => true,
    //                     'data' => [
    //                         'type' => $request->get('type'),
    //                         'user' => $collaborator,
    //                     ],
    //                 ], 200);
    //             }else{
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'No existe usuario asociado a este folio: ' . $credentials['folio'],
    //                     'data' => $collaborator,
    //                 ], 422);
    //             }
    //             break;
    //         default:
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Wrong type.',
    //             ], 401);             
    //             break;
    //     }
    // }
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function loginV2(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'typeAuth' => 'required',
    //         'credentials' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => array_merge($validator->errors()->get("typeAuth"), $validator->errors()->get("credentials"))
    //         ], 422);
    //     }

    //     switch ($request->get('typeAuth')) {
    //         case 'user/password':

    //             $validator = Validator::make($request->get('credentials'), [
    //                 'email' => 'required|email|exists:users,email',
    //                 'password' => 'required',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Wrong validation.',
    //                     'errors' => $validator->errors()
    //                 ], 422);
    //             }

    //             $credentials = collect($request->get('credentials'));
    //             $credentials = $credentials->only(['email', 'password'])->toArray();

    //             $token = JWTAuth::attempt($credentials);

    //             if ($token) {
    //                 $user = User::with('apps', 'collaborator', 'roles')
    //                         ->where('email', $credentials['email'])
    //                         ->get()
    //                         ->first();

    //                 $user->type = 'admin';

    //                 $user->defaultAppUrl = $user->apps->first()->url;

    //                 return response()->json([
    //                     'success' => true,
    //                     'data' => [
    //                         'session' => [
    //                             'token' => $token,
    //                             'typeAuth' => 'user/password',
    //                             'user' => collect($user)->except("config"),
    //                             'collaborator' => $user->collaborator,
    //                         ],
    //                     ],
    //                 ], 200, [], JSON_NUMERIC_CHECK);           
    //             }

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Wrong credentials.',
    //                 'errors' => [
    //                     'email' => ['No se ha encontrado un usuario con estas credenciales.'],
    //                     'password' => ['No se ha encontrado un usuario con estas credenciales.'],
    //                 ]
    //             ], 401);   

    //         case 'folio':
    //             $validator = Validator::make($request->get('credentials'), [
    //                 'folio' => 'required|integer|exists:nom.collaborators,id',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Wrong validation.',
    //                     'errors' => $validator->errors()
    //                 ], 422);
    //             }                

    //             $collaborator = Collaborator::find($request->get('credentials'))->first();
    //             $collaborator->apps = [["id" => 1,"url" => "https://spa.pinturasacuario.mx/rh/", 'name' => 'colaboradores']];
    //             $collaborator->defaultAppUrl = "https://spa.pinturasacuario.mx/ti";

    //             $user = $collaborator;

    //             $user->type = 'user';

    //             return response()->json([
    //                 'success' => true,
    //                 'data' => [
    //                     'session' => [
    //                         'token' => null,
    //                         'typeAuth' => 'folio',
    //                         'user' => $user,
    //                         'collaborator' => $collaborator,
    //                     ],
    //                 ],
    //             ], 200, [], JSON_NUMERIC_CHECK);

    //         default:
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Típo de autenticación no reconocida.',
    //             ], 422);             
    //             break;
    //     }
    // } 

    // public function user(Request $request) {
    //     $user = Collaborator::find($request->get('id'));

    //     return response()->json([
    //         'success' => true,
    //         'data' => $user
    //     ],200);
    // }

    public function logout() {
        $token = JWTAuth::getToken();

        JWTAuth::invalidate($token);

        return response()->json([
            'success' => true,
            'data' => []
        ],200);
    }
}
