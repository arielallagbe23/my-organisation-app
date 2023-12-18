<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users',
            ]);
            if ($validator->fails()) {           
                return response()->json(['message' => 'L\'adresse e-mail et ou le numéro de téléphone est déjà utilisé.'], 400);
            }
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->type;
            $user->save();   
            return response()->json($user, 200);
    }

    public function login (Request $request) 
    {
        if (!Auth::attempt($request->only('email','password')))
        {
            return response ([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }

    public function user()
    {
        $user = auth()->user();
    
        if ($user) {
            $currentDayOfWeek = now()->dayOfWeek; // Obtenez le jour de la semaine actuel (1 pour lundi, 2 pour mardi, etc.)
    
            $userMenus = DB::table('menus')
                ->join('types', 'menus.type_id', '=', 'types.id')
                ->where('menus.user_id', $user->id)
                ->where('menus.day_of_week', $currentDayOfWeek) // Filtrez les menus pour le jour actuel
                ->orderBy('menus.day_of_week')
                ->orderBy('types.id')
                ->orderBy('menus.type_id')
                ->orderBy('menus.id')
                ->select('menus.*', 'types.nom_type as type_name')
                ->get();
    
            if ($userMenus->isEmpty()) {
                return response()->json(['user' => $user, 'message' => 'Cet utilisateur n\'a créé aucun menu pour aujourd\'hui']);
            } else {
                return response()->json(['user' => $user, 'menus' => $userMenus->values()]);
            }
        } else {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }
    }
    
    

    public function logout (Request $request) 
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });
    
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * Update the specified User in storage.
     */
    public function updateUser(Request $request, string $id)
    {
        
        $user = User::findOrFail($id);
        $user->update($request->all());
        $user->save();
        $message = 'User updated successfully';
        return response()->json(['message' => $message, 'user' => $user], 200);
    }

    /**
     * Remove the specified User from storage.
     */
    public function destroyUser(string $id)
    {
        $user = User::find($id);
        if (!$user) {
        $message = 'User not found';
        return response()->json(['message' => $message], 404);
        }
        $user->delete();
        $message = 'User deleted successfully';
        return response()->json(['message' => $message], 200);
    }

}
