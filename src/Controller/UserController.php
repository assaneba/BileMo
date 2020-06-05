<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Rest\Get(
     *     path= "/users/my-profile",
     *     name= "user_edit"
     * )
     * @Rest\View(statusCode= 200)
     */
    public function viewProfile()
    {
        return $user = $this->getUser();
    }
}
