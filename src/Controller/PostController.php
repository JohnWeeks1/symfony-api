<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Responses\Post\PostResponse;
use App\Responses\User\UserResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Responses\Comment\CommentResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Responses\Post\PostWithRelatedUserResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{

    /**
     * Entity Manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Post Repository.
     *
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * User Repository.
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Posts with related users response.
     *
     * @var PostWithRelatedUserResponse
     */
    protected $postWithRelatedUserResponse;

    /**
     * Posts with related users response.
     *
     * @var CommentResponse
     */
    protected $commentResponse;

    /**
     * Posts response.
     *
     * @var PostResponse
     */
    protected $postResponse;

    /**
     * Posts response.
     *
     * @var UserResponse
     */
    protected $userResponse;


    /**
     * PostController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PostRepository $postRepository
     * @param UserRepository $userRepository
     * @param CommentResponse $commentResponse
     * @param PostResponse $postResponse
     * @param UserResponse $userResponse
     * @param PostWithRelatedUserResponse $postWithRelatedUserResponse
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PostRepository $postRepository,
        UserRepository $userRepository,
        CommentResponse $commentResponse,
        PostResponse $postResponse,
        UserResponse $userResponse,
        PostWithRelatedUserResponse $postWithRelatedUserResponse
    )
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->commentResponse = $commentResponse;
        $this->postResponse = $postResponse;
        $this->userResponse = $userResponse;
        $this->postWithRelatedUserResponse = $postWithRelatedUserResponse;
    }

    /**
     * Get all posts.
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPostsAction()
    {
        $posts = $this->postRepository->findAll();

        $postsWithRelatedUser = ['posts' => []];
        foreach ($posts as $post) {
            $postsWithRelatedUser['posts'][] = $this->postWithRelatedUserResponse->handle($post);
        }

        return $this->json($postsWithRelatedUser, 200);
    }

    /**
     * Get post by id.
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPostAction(int $id)
    {
        $post = $this->postRepository->find($id);

        $postWithUserAndComments = $this->postRepository->serializedPostWithUserAndComments($post);

        return $this->json($postWithUserAndComments, 200);
    }

    /**
     * Create a new post.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postPostAction(Request $request)
    {
        try {
            $user = $this->userRepository->find(
                $request->get('user_id')
            );

            $post = new Post;
            $post->setUser($user);
            $post->setTitle($request->get('title'));
            $post->setContent($request->get('content'));

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->json('New post created!', 200);
        } catch (Exception $e) {
            return $this->json($e->getMessage());
        }

    }

    public function patchPostAction(Request $request, int $id)
    {
        try {
            $post = $this->postRepository->find($id);

            $post->setTitle($request->get('title'));
            $post->setContent($request->get('content'));

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->json('Post with id ' . $post->getId() . ' was updated', 200);
        } catch (Exception $e) {
            return $this->json($e->getMessage());
        }

    }

    /**
     * Delete post.
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deletePostAction(int $id)
    {
        try {
            $post = $this->postRepository->find($id);

            $this->entityManager->remove($post);
            $this->entityManager->flush();

            return $this->json('Post deleted', 200);

        } catch (\Exception $e) {
            return $this->json($e->getMessage());
        }
    }
}
