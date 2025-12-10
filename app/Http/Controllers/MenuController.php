<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;

class MenuController extends Controller
{
    // GET /api/menus  => kategori + menunya (aktif)
    public function index()
    {
        $categories = Category::active()
            ->with(['menus' => fn($q) => $q->active()->orderBy('name')])
            ->orderBy('sort')
            ->get();

        return response()->json($categories);
    }

    // GET /api/menus/{menu}
    public function show(Menu $menu)
    {
        return response()->json($menu);
    }
}
