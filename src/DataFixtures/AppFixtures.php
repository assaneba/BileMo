<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->phoneData() as $aPhoneArray) {
            $phone = new Phone();
            $phone->setBrand($aPhoneArray["brand"])
                  ->setModel($aPhoneArray["model"])
                  ->setPrice($aPhoneArray["price"])
                  ->setDescription($aPhoneArray["description"])
                  ->setReleaseDate(new \DateTime($aPhoneArray["releaseDate"]))
                ;
            $manager->persist($phone);
        }

        foreach ($this->userData() as $aUserArray) {
            $user = new User();
            $password = $this->encoder->encodePassword($user, $aUserArray["password"]);
            $user->setEmail($aUserArray["email"])
                ->setRole(["ROLE_ADMIN"]);
            $user->setPassword($password);

            $manager->persist($user);
        }

        foreach ($this->customerData() as $aCustomArray) {
            $customer = new Customer();
            $customer->setFirstName($aCustomArray["firstName"])
                     ->setLastName($aCustomArray["lastName"])
                     ->setEmail($aCustomArray["email"])
                     ->setLogin($aCustomArray["login"])
                     ->setUser($user)
                ;
            $manager->persist($customer);
        }

        $manager->flush();
    }

    public function userData()
    {
        $jsonData = '
        [
            {"email":"gstarte0@phoca.cz","password":"passer12345"},
            {"email":"spawelek1@bravesites.com","password":"passer12345"},
            {"email":"twebburn2@nydailynews.com","password":"passer12345"}
        ]';

        return json_decode($jsonData, true);
    }

    public function customerData()
    {
        $jsonData = '
        [
            {"firstName": "Gemma","lastName": "Fleming","email": "interdum.ligula.eu@vitae.com","login": "vitae"},
            {"firstName": "Illana","lastName": "Heath","email": "amet@at.org","login": "libero"},
            {"firstName": "Tobias","lastName": "Young","email": "lorem@aduiCras.net","login": "ligula"},
            {"firstName": "Joshua","lastName": "Bonner","email": "faucibus.Morbi@duiSuspendisseac.com","login": "varius"},
            {"firstName": "Emerson","lastName": "Le","email": "dictum@Donecvitae.co.uk","login": "senectus"},
            {"firstName": "Keegan","lastName": "Gregory","email": "lectus.pede.ultrices@Vivamus.net","login": "lacus"},
            {"firstName": "Ryan","lastName": "Salas","email": "montes@sit.co.uk","login": "orci"},
            {"firstName": "Diana","lastName": "Lindsay","email": "sem@molestiedapibus.net","login": "In"},
            {"firstName": "Todd","lastName": "Carrillo","email": "Pellentesque@semelit.org","login": "eu"},
            {"firstName": "Garrison","lastName": "Waller","email": "Nulla.interdum.Curabitur@estacfacilisis.com","login": "dictum."},
            {"firstName": "Keane","lastName": "Dorsey","email": "urna.suscipit@a.com","login": "porttitor"},
            {"firstName": "Isabelle","lastName": "Harris","email": "dictum.eu.placerat@placeratCrasdictum.com","login": "quam"},
            {"firstName": "Travis","lastName": "Shepherd","email": "montes.nascetur@inlobortistellus.com","login": "nec,"},
            {"firstName": "Hope","lastName": "Acevedo","email": "ornare@sedduiFusce.ca","login": "Sed"},
            {"firstName": "Herrod","lastName": "Rollins","email": "Quisque.varius@cursusIntegermollis.org","login": "Donec"}
        ]';

        return json_decode($jsonData, true);
    }

    public function phoneData()
    {
        $jsonData ='[{"model":11,"brand":"Huawei","price":1581,"description":"Curabitur egestas nunc sed libero. Proin sed turpis nec mauris blandit mattis. Cras eget nisi dictum augue malesuada malesuada. Integer id magna et ipsum cursus vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros non","releaseDate":"2019-03-20 11:03:14"},{"model":9,"brand":"Iphone","price":882,"description":"amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu","releaseDate":"2018-11-13 02:08:20"},{"model":11,"brand":"Iphone","price":1566,"description":"erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas","releaseDate":"2018-06-21 10:01:01"},{"model":10,"brand":"Huawei","price":1172,"description":"a","releaseDate":"2017-12-26 17:41:59"},{"model":11,"brand":"HTC","price":1540,"description":"commodo tincidunt nibh. Phasellus nulla. Integer vulputate, risus a ultricies adipiscing, enim mi tempor lorem, eget mollis lectus pede et risus. Quisque libero lacus, varius et, euismod","releaseDate":"2018-04-30 17:45:20"},{"model":9,"brand":"Samsung","price":1051,"description":"lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer","releaseDate":"2019-02-16 21:21:36"},{"model":9,"brand":"Iphone","price":953,"description":"sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh.","releaseDate":"2020-04-19 05:50:43"},{"model":11,"brand":"HTC","price":1342,"description":"adipiscing lacus. Ut nec urna et arcu imperdiet ullamcorper. Duis at lacus. Quisque purus sapien, gravida non, sollicitudin a, malesuada id, erat. Etiam vestibulum massa rutrum magna. Cras convallis convallis dolor.","releaseDate":"2019-05-25 09:41:24"},{"model":11,"brand":"Samsung","price":1096,"description":"et","releaseDate":"2019-02-05 20:44:02"},{"model":11,"brand":"Iphone","price":940,"description":"purus. Maecenas libero","releaseDate":"2018-06-28 01:06:11"},{"model":10,"brand":"Huawei","price":1345,"description":"amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac","releaseDate":"2018-01-13 20:36:36"},{"model":9,"brand":"Huawei","price":1537,"description":"ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis","releaseDate":"2018-08-26 15:52:49"},{"model":9,"brand":"Iphone","price":1545,"description":"odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque tellus, imperdiet non, vestibulum nec, euismod in, dolor. Fusce feugiat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor, velit eget laoreet","releaseDate":"2018-11-29 13:27:42"},{"model":9,"brand":"Huawei","price":1112,"description":"non enim","releaseDate":"2018-12-09 01:46:18"},{"model":9,"brand":"Samsung","price":1098,"description":"erat, in consectetuer ipsum","releaseDate":"2017-12-23 21:12:14"},{"model":9,"brand":"HTC","price":1100,"description":"vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat","releaseDate":"2018-04-14 08:01:57"},{"model":11,"brand":"Iphone","price":1135,"description":"sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget","releaseDate":"2019-03-15 11:17:29"},{"model":9,"brand":"Huawei","price":1033,"description":"sagittis placerat. Cras dictum ultricies ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo. Praesent luctus. Curabitur egestas nunc sed libero. Proin sed turpis nec mauris","releaseDate":"2019-09-28 10:09:13"},{"model":9,"brand":"Samsung","price":1132,"description":"sit amet ante. Vivamus","releaseDate":"2018-07-19 06:55:21"},{"model":10,"brand":"Samsung","price":1163,"description":"ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac sem ut","releaseDate":"2020-02-22 23:03:06"},{"model":10,"brand":"Iphone","price":1247,"description":"pede. Nunc sed orci lobortis augue scelerisque mollis. Phasellus libero mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet","releaseDate":"2019-07-18 20:19:54"},{"model":10,"brand":"HTC","price":1278,"description":"lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim,","releaseDate":"2019-08-02 23:38:46"},{"model":10,"brand":"Huawei","price":948,"description":"congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique","releaseDate":"2019-01-23 05:39:51"},{"model":11,"brand":"Samsung","price":1503,"description":"eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi","releaseDate":"2019-10-10 19:10:04"},{"model":9,"brand":"HTC","price":1542,"description":"placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna","releaseDate":"2018-10-22 16:56:17"}]';

        return json_decode($jsonData, true);
    }
}
