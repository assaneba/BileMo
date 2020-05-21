<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhoneController
 * @package App\Controller
 * @Route("/api", name="api")
 */
class PhoneController extends AbstractController
{
    /**
     * @Route("/phone", name="phone")
     */
    public function index()
    {
        $data = [
          "name"  => "Iphone",
          "price" => "2000"
        ];

        return new JsonResponse($data);
    }
}
