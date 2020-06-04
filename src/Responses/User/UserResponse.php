<?php


namespace App\Responses\User;

use App\Entity\User;

class UserResponse
{
    /**
     * Handle request.
     *
     * @param User $user
     *
     * @return array
     */
    public function handle(User $user)
    {
        return [
            'id' => $user->getId(),
            'content' => $user->getEmail()
        ];
    }
}
