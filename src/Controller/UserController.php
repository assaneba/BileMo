<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as OA;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Rest\Get(
     *     path= "/users/{id}",
     *     name= "user_edit"
     * )
     * @Rest\View(statusCode= 200)
     *
     * @OA\Get(
     *     @OA\Response(response="200", description="Return your profile details")
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     type="number",
     *     description="Your id number"
     * )
     */
    public function viewProfile()
    {
        return $user = $this->getUser();
    }
}
