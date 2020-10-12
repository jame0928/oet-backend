<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Validator;

class TokensController extends Controller
{
    //
    public function login(Request $request){

        $token = JWTAuth::getToken();

        if(!$token || (isset($request->username) || isset($request->password))){
            $credentials = $request->only('username','password');

            $validator = Validator::make($credentials, [
                'username' => 'required|email',
                'password' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $token = JWTAuth::attempt($credentials);

            if($token){
                $user = Auth::user();
                $user->accessToken = $token;
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong credentials',
                    'errors' => $validator->errors()
                ], 401);
            }
             
        } else{
            $user = JWTAuth::authenticate($token);   
            $user->accessToken = $token->get();           
        }   	
    	if($token){

            if($user){                          
                return response()->json($user, 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Need to login again! (expired)',
                ], 422);
            }
    		
    	}else{
    		return response()->json([
    			'success' => false,
    			'message' => 'Need to login again!',
    		], 401);
    	}
    }


    public function refreshToken(){
    	$token = JWTAuth::getToken();

    	try{
    		$token = JWTAuth::refresh($token);

    		return response()->json([
    			'success' => true,
    			'token' => $token,
    		], 200);
    	} catch(TokenExpireddException $e){
    		return response()->json([
    			'success' => false,
    			'message' => 'Need to login again! (expired)',
    		], 422);
    	} catch(TokenBlacklistedException $e){
    		return response()->json([
    			'success' => false,
    			'message' => 'Need to login again! (blacklisted)',
    		], 422);
    	}
    }


    public function logout(){
    	$token = JWTAuth::getToken();

    	try{
    		JWTAuth::invalidate($token);

    		return response()->json([
    			'success' => true,
    			'message' => 'Logout successful',
    			'token' => $token,
    		], 200);

    	} catch(JWTException $e){
    		return response()->json([
    			'success' => false,
    			'message' => 'Failed logout, please try again!',
    		], 422);
    	}
    }
}
