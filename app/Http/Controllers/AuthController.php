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
        
        /* $credentials = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);*/
       

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

    public function login( Request $request){   //Este Request proviene de un form
        
       
        //Si el usuario existe y el password hasheado que se pasa coincide con el hasheado guardado.
        if( ! Auth::attempt( $request->only('email','password'))){
            return response()->json(['message'=>'Unauthorized'],401);
        }

        $user = User::where('email', $request['email'])->firstOrfail(); //Similar a first() pero en caso de no encontrar nada arroja error en lugar de null.

       $token = $user->createToken('auth_token')->plainTextToken;
       
        
        //return redirect()->intended('dashboard');
        
        return response()->json( [
            'message'=>'hi',$user->name,
            'acces_token'=> $token,
            'token_type'=>'bearer',
            'user'=>$user
        ]);

        
    }


    //Logout 
    public function logout (){

        // Get user who requested the logout
        $user = request()->user(); //or Auth::user(), pero no trae el metodo tokens()
        $user->tokens()->delete();
        
        return "token eliminado, va a tirar unauthorized por middleware";
    }


    //Controlador para probar todas las facades de User:: de Auth:: y los metodos de auth() y user(), con el middleware sanctum
    public function pruebas() {
        //$user= User::create([])   //Nada nuevo, el modelo crea un registro nuevo.
        //$user->createToken('auth_token')->plainTextToken; //Crea un token y sanctum entiende que este $user esta autenticado
        //Auth::authenticate($user)     // Authentica el usuario y/o retorna el usuario authenticado
        //$user= Auth::user();      //El usuario authenticado, o Unauthenticated1
        //Auth::attempt(['email' => $email, 'password' => $password])  //True si el usuario existe y el password hasheado coincide con el hasheado guardado
        //return Auth::authenticate(); // Retorna el usuario authenticado
        //return Auth::check(); //Retorna 1 si hay usuario authenticado, solo se usa cuando no hay middleware sino no tiene sentido
        
        $user = User::where('email','topo2@example.com')->firstOrfail(); //Similar a first() pero en caso de no encontrar nada arroja error en lugar de null.
       $token = $user->createToken('auth_token')->plainTextToken;

       return response()->json( [
        'message'=>'hi',$user->name,
        'acces_token'=> $token,
        'token_type'=>'bearer',
        'user'=>$user
    ]);
    }
}
