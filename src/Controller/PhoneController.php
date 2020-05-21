<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
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
     * @Route("/phones", name="phone_list", methods={"GET"})
     */
    public function allPhones(PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
        $phones = $phoneRepository->findAll();
        $data = $serializer->serialize($phones, 'json', [
            'groups' => 'list'
        ]);

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/phones/{id}", name="show_phone", methods={"GET"})
     */
    public function aPhone($id, PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
        $phone = $phoneRepository->find($id);
        $data = $serializer->serialize($phone, 'json', [
            'groups' => 'detail'
        ]);

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

}
