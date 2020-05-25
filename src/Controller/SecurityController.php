<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Rest\Post(
     *     path="/login",
     *     name="login"
     * )
     * @Rest\View(
     *     statusCode= 200
     * )
     */
    public function login()
    {
        $user = $this->getUser();

        return $user;
    }
}
