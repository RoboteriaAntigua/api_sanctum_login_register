# API con laravel sanctum
    Para apis basadas en tokens. Ver personal_acces_token en migraciones

# Instalar
    laravel new nombre o
    composer create-project laravel/laravel nombre

#  Instalar sanctum
    composer require laravel/sanctum

# El .env

# migrar:
    php artisan migrate


# Trabajamos con una tabla productos:
    php artisan make:model Productos -mcr


# Un controlador para el login y registro de usuarios:
    php artisan make:controller AuthController

# COmpleto la migracion de productos con las columnas que conlleva y corro migra:
    php artisan migrate

# Creo un seeder para la tabla productos:
    php artisan make:seeder ProductoSeeder
         DB::table('productos')->insert([
            'name' => 'iphone 123',
            'description' => 'un telefono',
            'price' => 900
        ]);

# Usar un ProductSeeder o xSeeder, require estas lineas en el databaseSeeder:
    $this->call([
            ProductoSeeder::class,
        ]);
    php artisan db:seed

# Creo una ruta en el api.php y pruebo en postman: 
    Route::get('products', [ProductosController::class, 'index']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    http://127.0.0.1:8000/api/products


# Creo el metodo register en AuthController y lo pruebo:
    Aqui vamos a usar el modelo User::create() y retornamos un token. 
    Tambien aplicamos una validacion
    
# Probar el metodo register en postman:
    headers:
        Accept -> application/json
        content-Type -> application/json
    Body: name, email, y password

    Esto retorna:
                    {
                "data": {
                    "name": "otro",
                    "email": "john.doe23@example.com",
                    "updated_at": "2024-02-16T20:33:06.000000Z",
                    "created_at": "2024-02-16T20:33:06.000000Z",
                    "id": 2
                },
                "access_token": "1|lwzfLGjYaTHdmTlWQWsDSQHRDYJThVoDbOcJ9OPK9fd040c2",
                "token_type": "Bearer"
            }

# Creo el metodo login en AuthController y lo pruebo:
    Auth::attempt()
        Se le pasa un arreglo.
        Usando el mail como clave primaria:
        Si el usuario es encontrado, se compara la clave que se pasa en el array hasheada con la hasheada guardada en la DB. (no hace falta hashearla el framework lo hace auto) 
        Retorna token si todo bien, o unauthorized, 401 si no coincide.

    Lo probamos pero en lugar de mandarle plano le mandamos form-data solo de email y password. 
    (Que coincida con alguno ya guardado)


# Creo el metodo Logout del AuthController:
    1# Protejo la ruta con el middleware('auth:sanctum)
    2# Cuando lo pruebo le agrego en autorizations el token del usuario que hicimos login
    3# Borro el token de usuario:
         $user = request()->user(); //or Auth::user()
            $user->tokens()->delete();

# Continuar metodo pruebas
# Facades y helpers de Auth:: , User:: 
    $user= User::create([])                         //Nada nuevo, el modelo crea un registro nuevo.
    $user->createToken('auth_token')->plainTextToken; //Crea un token y sanctum entiende que este $user esta autenticado
    Auth::authenticate($user)                       // Authentica el usuario y/o retorna el usuario authenticado
    $user= Auth::user();                            //El usuario authenticado, o Unauthenticated1
    Auth::attempt(['email' => $email, 'password' => $password])  //True si el usuario existe y el password hasheado coincide con el hasheado guardado
    return Auth::authenticate();                    // Retorna el usuario authenticado
    return Auth::check();                           //Retorna 1 si hay usuario authenticado, solo se usa cuando no hay middleware sino no tiene sentido

# Para mejorar, incluir sesiones y middleware personalizado