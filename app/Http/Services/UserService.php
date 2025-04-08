<?php

namespace App\Http\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function createUser(array $data): array
    {
        try {
            DB::beginTransaction();

            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Usuário criado com sucesso',
                'data' => new UserResource($user)
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar usuário: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Falha ao criar usuário',
                'error' => $e->getMessage()
            ];
        }
    }

    public function getUserById(int $id): array
    {
        $user = User::find($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        return [
            'success' => true,
            'message' => 'Usuário encontrado com sucesso',
            'data' => new UserResource($user)
        ];
    }

    public function updateUser(int $id, array $data): array
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Usuário não encontrado'
                ];
            }

            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $user->update($data);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Usuário atualizado com sucesso',
                'data' => new UserResource($user->fresh())
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar usuário: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Falha ao atualizar usuário',
                'error' => $e->getMessage()
            ];
        }
    }

    public function deleteUser(int $id): array
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Usuário não encontrado'
                ];
            }

            $user->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Usuário removido com sucesso'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao remover usuário: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Falha ao remover usuário',
                'error' => $e->getMessage()
            ];
        }
    }
}
