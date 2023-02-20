<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['Auth']]);
    }

    public function Auth(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $credentials = ['nik' => $request->nik, 'password' => $request->password];

        $token = Auth::attempt($credentials);    

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        
        $user = Auth::user();

        // $token = auth()->attempt( $credentials );

        // if (! $auth ) return response()->json([ 'message' => ['Unauthorized'] ], 401);

        // $user = User::where([
        //     'nik' => $request->nik,
        // ])->first();

        // $bundleData = [
        //     "role" =>  self::userRole($user->id),
        //     "password" => bcrypt($request->password),
        //     "nik" => $request->nik
        // ];
        
        // if (! $token = auth( 'api' )->login( $user ) ) {
        //     return response()->json([ 'message' => ['Unauthorized'] ], 401);
        // }

        // dd($user);
        // $this->fromUser($user);
        // $token = JWTAuth::fromUser($user);
        
        $bundleData = [
            "role" =>  self::userRole($user->id),
            "password" => bcrypt($request->password),
            "nik" => $request->nik
        ];
     

        return $this->respondWithToken($token, $bundleData);
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh(), []);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $bundleData)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    protected static function userRole($user_id)
    {
       return RoleUser::where(['user_id' => $user_id])->get()->toArray();
    }

    public function registerUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'nik' => 'required|min:16|max:16|unique:users,nik'
        ]);

        if($validate->fails()) {
            return response()->json($validate->messages()->first());
        }

        $dataPush = [
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'nik' => $request->nik
        ];

        try {
            //code...
            User::create($dataPush);

            return response()->json([
                'message' => "Create data success"
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }

    public function testing()
    {
        return response()->json('masuk');
    }
}

