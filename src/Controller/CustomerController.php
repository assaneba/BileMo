<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\ArrayTransformerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/api/customers")
 */
class CustomerController extends AbstractController
{
    /**
     * @param CustomerRepository $customerRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return iterable
     *
     * @Rest\Get(
     *     path="/",
     *     name="customers_list"
     * )
     * @Rest\View(statusCode= 200)
     */
    public function allCustomers(CustomerRepository $customerRepository, PaginatorInterface $paginator, Request $request)
    {
        $query = $customerRepository->allCustomersQuery($this->getUser());
        $paginatedProducts = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $paginatedProducts->getItems();
    }

    /**
     * @param Customer $customer
     * @return Customer
     *
     * @Rest\Get(
     *     path="/{id}",
     *     name="customer_show"
     * )
     * @Rest\View(statusCode= 200)
     */
    public function aCustomer(Customer $customer)
    {
        return $customer;
    }


    /**
     * @param Customer $customer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @return Customer
     * @throws \Exception
     *
     * @Rest\Post(
     *     path="/",
     *     name="customer_add"
     * )
     * @Rest\View(statusCode= 201)
     * @ParamConverter("customer", converter="fos_rest.request_body")
     */
    public function addCustomer(Customer $customer, EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $errors = $validator->validate($customer);
        if(count($errors)) {
            throw new \Exception($errors);
        }
        $customer->setUser($this->getUser());

        $manager->persist($customer);
        $manager->flush();

        return $customer;
    }

    /**
     * @param Customer $customer
     * @param EntityManagerInterface $manager
     *
     * @Rest\Delete(
     *     path="/{id}",
     *     name="customer_delete"
     * )
     * @Rest\View(statusCode= 204)
     */
    public function deleteCustomer(Customer $customer, EntityManagerInterface $manager)
    {
        $manager->remove($customer);
        $manager->flush();
    }


    /**
     * @param Customer $customer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param Request $request
     * @param ArrayTransformerInterface $arrayTransformer
     * @return object|null
     * @throws \Exception
     *
     * @Rest\Put(
     *     path="/{id}",
     *     name="customer_update"
     * )
     * @Rest\View(statusCode= 200)
     * @ParamConverter("customer", converter="fos_rest.request_body")
     */
    public function updateCustomer(Customer $customer, EntityManagerInterface $manager, ValidatorInterface $validator, Request $request, ArrayTransformerInterface $arrayTransformer)
    {
        $customerUpdater = $manager->getRepository(Customer::class)->find($request->get('id'));

        // Check if some values have changed and set them in existing object
        $customer = $arrayTransformer->toArray($customer);
        foreach ($customer  as $key => $value) {
            if($key && !empty($value)) {
                $keyPiece = explode('_', $key);

                foreach ($keyPiece as $index => $piece) {
                    $piece = ucfirst($piece);
                    $keyPiece[$index] = $piece;
                }
                $name = implode($keyPiece);
                $setter = 'set'.$name;
                $customerUpdater->$setter($value);
            }
        }

        $errors = $validator->validate($customerUpdater);
        if (count($errors)) {
            throw new \Exception('Invalid argument(s) detected');
        }
        $manager->flush();

        return $customerUpdater;
    }
}
