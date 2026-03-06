<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repository,
    ) {
    }

    /**
     * Create a new user. Bcrypt password if present.
     */
    public function createUser(array $data): Model
    {
        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $this->repository->create($data);
    }

    /**
     * Update user. Bcrypt password if present, otherwise remove it from data.
     */
    public function updateUser(int|string $id, array $data): bool
    {
        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Delete a user.
     */
    public function deleteUser(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Change a user's password after verifying the old one.
     *
     * @return bool True on success, false if old password doesn't match.
     */
    public function changePassword(Model $user, string $oldPassword, string $newPassword): bool
    {
        if (! Hash::check($oldPassword, $user->password)) {
            return false;
        }

        $user->password = bcrypt($newPassword);
        $user->save();

        return true;
    }
}
