<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;

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

    public function store(UserRequest $request)
    {
        return $this->service->createUser($request->validated());
    }

    public function show($id)
    {
        return $this->service->showUser($id);
    }

    public function update(UserRequest $request, int $id)
    {
        return $this->service->updateUser($request->validated(), $id);
    }

    public function destroy(int $id)
    {
        return $this->service->deleteUser($id);
    }
}
