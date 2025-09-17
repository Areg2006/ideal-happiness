<?php

namespace App\Http\Controllers;

use App\DTO\UserStoreDTO;
use App\DTO\UserUpdateDTO;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
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

    public function store(UserStoreRequest $request)
    {
        $dto = new UserStoreDTO(
            name: $request->getName(),
            email: $request->getEmail(),
            password: $request->getPassword(),
            role: $request->getRole(),
            balance: $request->getBalance()
        );

        $user = $this->service->createUser($dto);

        return response()->json($user);
    }

    public function show($id)
    {
        return $this->service->showUser($id);
    }

    public function update(UserUpdateRequest $request)
    {
        $dto = new UserUpdateDTO(
            id: $request->getID(),
            name: $request->getName(),
            email: $request->getEmail(),
            role: $request->getRole(),
            balance: $request->getBalance()
        );

        $user = $this->service->updateUser($dto);

        return response()->json($user);
    }


    public function destroy(int $id)
    {
        return $this->service->deleteUser($id);
    }
}
