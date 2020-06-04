<?php


namespace App\Responses\Comment;

use App\Entity\Comment;

class CommentResponse
{
    /**
     * Handle request.
     *
     * @param Comment $comment
     *
     * @return array
     */
    public function handle(Comment $comment)
    {
        return [
            'id' => $comment->getId(),
            'content' => $comment->getContent()
        ];
    }
}
