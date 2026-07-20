<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EmployeeService
{
    public function getAllEmployeesExcept(int $userId, ?string $search = null)
    {
        $query = User::where('id', '!=', $userId);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });
    }

    return $query->orderBy('created_at', 'desc')
                 ->paginate(10)
                 ->withQueryString();
    }

    public function createEmployee(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        
        if (isset($data['photo'])) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        
        return User::create($data);
    }

    public function updateEmployee(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        
        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->role  = $data['role'];
        
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        
        if (isset($data['photo'])) {
            $this->deleteOldPhoto($user->photo);
            $user->photo = $this->uploadPhoto($data['photo']);
        }
        
        $user->save();
        
        return $user;
    }

    public function deleteEmployee(int $id, int $currentUserId): void
    {
        $user = User::findOrFail($id);
        
        if ($user->id === $currentUserId) {
            throw new \Exception('Anda tidak bisa menghapus akun sendiri');
        }
        
        $this->deleteOldPhoto($user->photo);
        $user->delete();
    }

    private function uploadPhoto($photo): string
    {
        return $photo->store('photos', 'public');
    }

    private function deleteOldPhoto(?string $photoPath): void
    {
        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }
    }
}