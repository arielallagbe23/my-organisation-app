<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allMenu()
    { 
        $menus = DB::table('menus')->get();

        if ($menus->isEmpty()) {
            return response()->json(['message' => 'Aucun menu trouvé'], 404);
        }

        return response()->json($menus);
    }

    public function allMenu2()
    {
        $user = auth()->user();
    
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }
    
        // Sélectionnez un menu pour chaque type de repas
        $breakfast = Menu::where('type_id', 1)->inRandomOrder()->first();
        $lunch = Menu::where('type_id', 2)->inRandomOrder()->first();
        $snack = Menu::where('type_id', 3)->inRandomOrder()->first();
        $dinner = Menu::where('type_id', 4)->inRandomOrder()->first();
    
        $selectedMenus = [
            'breakfast' => $breakfast,
            'lunch' => $lunch,
            'snack' => $snack,
            'dinner' => $dinner,
        ];
    
        // Retournez les menus sélectionnés avec les menus de l'utilisateur
        $userMenus = DB::table('menus')
            ->join('types', 'menus.type_id', '=', 'types.id')
            ->where('menus.user_id', $user->id)
            ->select('menus.*', 'types.nom_type as type_name')
            ->get();
    
        return response()->json(['user' => $user, 'selectedMenus' => $selectedMenus, 'userMenus' => $userMenus]);
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function createMenu(Request $request)
    {
        
        $request->validate([
            'user_id' => 'required|integer',
            'type_id' => 'required|integer',
            'contenu' => 'required|string',
        ]);

        $menu = new Menu([
            'user_id' => $request->input('user_id'),
            'type_id' => $request->input('type_id'),
            'contenu' => $request->input('contenu'),
        ]);

        $menu->save(); 

        return response()->json(['message' => 'Menu créé avec succès','menu' => $menu], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function menu(string $id)
    {
        $menu = DB::table('menus')->find($id);

        if ($menu) {
            return response()->json($menu);
        } else {
            return response()->json(['message' => 'menu non trouvé'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateMenu(Request $request, $id)
    {
        $menu = DB::table('menus')->find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        DB::table('menus')->where('id', $id)->update($request->all());

        $menuUpdated = DB::table('menus')->find($id);

        $message = 'Menu updated successfully';
        return response()->json(['message' => $message, 'menu' => $menuUpdated], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteMenu($id)
    {
        $menu = DB::table('menus')->find($id);

        if (!$menu) {
            $message = 'Menu not found';
            return response()->json(['message' => $message], 404);
        }

        DB::table('menus')->where('id', $id)->delete();

        $message = 'Menu deleted successfully';
        return response()->json(['message' => $message], 200);
    }
}
