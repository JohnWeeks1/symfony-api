<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Post repository.
     *
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * CommentController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PostRepository $postRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PostRepository $postRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }

    /**
     * Create comment.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postCommentAction(Request $request)
    {
        try {
            $post = $this->postRepository->find(
                $request->get('post_id')
            );

            $comment = new Comment();
            $comment->setContent($request->get('content'));
            $comment->setPost($post);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->json('New comment created!', 200);
        } catch (Exception $e) {
            return $this->json($e->getMessage());
        }
    }
}
