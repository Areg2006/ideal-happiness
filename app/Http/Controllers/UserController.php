<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function index()
    {
        return $this->service->listUser();
    }

    public function store(Request $request)
    {
        return $this->service->createUser($request);
    }

    public function show($id)
    {
        return $this->service->showUser($id);
    }

    public function update(Request $request, int $id)
    {
        return $this->service->updateUser($request, $id);
    }

    public function destroy(int $id)
    {
        return $this->service->deleteUser($id);
    }
}
