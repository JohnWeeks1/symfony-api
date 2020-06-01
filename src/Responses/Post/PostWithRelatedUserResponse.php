<?php


namespace App\Responses\Post;

use App\Entity\Post;

class PostWithRelatedUserResponse
{
    /**
     * Handle request.
     *
     * @param Post $post
     *
     * @return array
     */
    public function handle(Post $post)
    {
        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'user' => [
                'id' => $post->getUser()->getId(),
                'email' => $post->getUser()->getEmail()
            ],
        ];
    }
}
