<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;




class AuthController extends Controller
{
    

    public function register( Request $request){
        
        //Validacion con facade Validator
        $validator = Validator::make( $request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }

        //Creo el usuario:
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        //Creo el token
        $token = $user->createToken('auth_token')->plainTextToken;
        
        //respuesta
        return response()->json( [ 
            'data'=> $user,
            'access_token'=> $token,
            'token_type'=> 'Bearer']);
    }

    public function Login( Request $request){
        
        //Si el usuario existe y el password hasheado que se pasa coincide con el hasheado guardado.
        if( ! Auth::attempt( $request->only('email','password'))){
            return response()->json(['message'=>'Unauthorized'],401);
        }

        $user = User::where('email', $request['email'])->firstOrfail(); //Similar a first() pero en caso de no encontrar nada arroja error en lugar de null.

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json( [
            'message'=>'hi',$user->name,
            'acces_token'=> $token,
            'token_type'=>'bearer',
            'user'=>$user
        ]);

    }
}
