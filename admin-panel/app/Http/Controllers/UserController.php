<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersReasource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends ApiController
{
    public function index(){
        $users = User::latest()->paginate(3);

        return $this->SuccessResponse([
            'users' => UsersReasource::collection($users),
            'links' => $users->links(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }
}
