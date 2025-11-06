<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function searchByEmail(Request $request)
    {
        $search = $request->query('q');
        $users = User::select('id_user', 'name', 'email')
            ->where('email', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%")
            ->limit(10)
            ->get();
        return response()->json($users);
    }
}
