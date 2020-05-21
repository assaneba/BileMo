<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PhoneController
 * @package App\Controller
 * @Route("/api", name="api")
 */
class PhoneController extends AbstractController
{
    /**
     * @Route("/phones", name="phone")
     */
    public function allPhones(SerializerInterface $serializer)
    {
        $phone = new Phone();
        $phone->setModel('X')
            ->setBrand('Iphone')
            ->setPrice(1200)
            ->setDescription('Super phone')
            ->setReleaseDate(new \DateTime());

        $data = $serializer->serialize($phone, 'json');

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

}
