<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Swagger\Annotations as OA;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Rest\Post(
     *     path="/login_check",
     *     name="login"
     * )
     * @Rest\View(statusCode= 200)
     *
     * @OA\Post(
     *     summary="fill your username and password (required field : username (your email), password) ",
     *     @OA\Response(response="200", description="Return a token for authentification")
     * )
     */
    public function login()
    {
        $user = $this->getUser();

        return $user;
    }

    /**
     * @Rest\Post(
     *     path="/register",
     *     name="register"
     * )
     * @Rest\View(statusCode= 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     *
     * @OA\Post(
     *     summary="Enter a email and password (required fields : email, password)",
     *     @OA\Response(response="200", description="Return a new user")
     * )
     */
    public function register(User $user, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_ADMIN"]);

        $manager->persist($user);
        $manager->flush();

        return $user;
    }
}
