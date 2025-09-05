<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;

class UserController extends Controller
{
       public function index(int $id)
       {   $user_id = $id;
           $user =User::find($id);
           if (!$user){
               return response()->json(['message' => "Нужно указать id"], 404);
           }
           $users =User::find($id)
           ->where('id', $user_id)->get();

           return UserResource::collection($users);
       }

       public function store(Request  $request): JsonResponse
       {
           $validated = $request->validate([
               'name' => 'required|string|max:255',
               'email' => 'required|email|max:255|unique:users,email',
               'password' => 'required|string|min:6',
               'role' => 'required|string|in:admin,user,partnership',
           ]);
           $user = User::create($validated);
           return response()->json($user);
       }

    public function show(int $id): JsonResponse
    {
        $user= User::with('products')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Клиент не найден'], 404);
        }

        return response()->json($user);
    }

        public function update(Request $request, int $id)
    {
        $user=User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Клиент не найден'], 404);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,user,partnership',
        ]);
        $user->update($request->all());
        return response()->json($user);
    }

        public function destroy(int $id)
    {
        $user=User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Клиент не найден'], 404);
        }
        $user->delete();
        return response(['massages' => 'Клиент удален']);
    }
}
