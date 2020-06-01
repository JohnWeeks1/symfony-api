<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * User repository.
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * User password encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Validator.
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * RegistrationController constructor.
     *
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * Register user.
     *
     * @Route("/api/register", name="api_register", methods={"POST"})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = new User();

            $user->setEmail($request->get('email'));
            $user->setRoles($user->getRoles());
            $user->setPassword($request->get('password'));

            $errors = $this->validator->validate($user);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return $this->json($errorsString);
            }

            $password = $this->passwordEncoder->encodePassword(
                $user,
                $request->get('password')
            );

            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->json('Registration successful!', 200);
        } catch (Exception $e) {
            return $this->json($e->getMessage());
        }
    }
}
