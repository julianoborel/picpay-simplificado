<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {

    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json([
            'success' => true,
            'message' => 'Lista de usuários obtida com sucesso',
            'data' => new UserCollection($users)
        ]);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $result = $this->userService->createUser($request->validated());

        return response()->json(
            $result,
            $result['success'] ? 201 : 400
        );
    }

    public function show(int $id): JsonResponse
    {
        $result = $this->userService->getUserById($id);

        return response()->json(
            $result,
            $result['success'] ? 200 : 404
        );
    }

    public function update(UserRequest $request, int $id): JsonResponse
    {
        $result = $this->userService->updateUser($id, $request->validated());

        return response()->json(
            $result,
            $result['success'] ? 200 : ($result['message'] === 'Usuário não encontrado' ? 404 : 400)
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->userService->deleteUser($id);

        return response()->json(
            $result,
            $result['success'] ? 200 : ($result['message'] === 'Usuário não encontrado' ? 404 : 400)
        );
    }
}
