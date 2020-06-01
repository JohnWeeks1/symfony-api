<?php


namespace App\Responses\Post;


use App\Entity\Post;

class PostsWithRelatedUsersResponse
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
