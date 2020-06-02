<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use function dump;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Rest\Put(
     *     path= "/change-password",
     *     name= "user_edit"
     * )
     * @Rest\View(statusCode= 200)
     * @ParamConverter("newUserData", converter="fos_rest.request_body")
     */
    public function changePassword(User $newUserData, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();

        $encodedPassword = $encoder->encodePassword($newUserData, $newUserData->getPassword());
        dump($newUserData->getPassword());

        if ($encodedPassword !== $user->getPassword()) {
            //$user->setPassword($encoder->encodePassword($newUserData, $newUserData->getPassword()));
            dump('Pas pareils !'. $encodedPassword);
        }
        else {
            dump('Pareils !');
        }

        //dump($newUserData);die;
    }
}
