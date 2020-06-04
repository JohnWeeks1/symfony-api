<?php

namespace App\Repository;

use App\Entity\Post;
use App\Responses\Post\PostResponse;
use App\Responses\User\UserResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Responses\Comment\CommentResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
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
     * PostRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param CommentResponse $commentResponse
     * @param PostResponse $postResponse
     * @param UserResponse $userResponse
     */
    public function __construct(
        ManagerRegistry $registry,
        CommentResponse $commentResponse,
        UserResponse $userResponse,
        PostResponse $postResponse
    )
    {
        parent::__construct($registry, Post::class);
        $this->commentResponse = $commentResponse;
        $this->postResponse = $postResponse;
        $this->userResponse = $userResponse;
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Post $post
     * @return array[]
     */
    public function serializedPostWithUserAndComments(Post $post)
    {
        $postWithUserAndComments = ['post' => []];
        $postWithUserAndComments['post'] = $this->postResponse->handle($post);
        $postWithUserAndComments['post']['user'] = $this->userResponse->handle($post->getUser());
        foreach ($post->getComments() as $comment) {
            $postWithUserAndComments['post']['comments'][] = $this->commentResponse->handle($comment);
        }

        return $postWithUserAndComments;
    }
}
