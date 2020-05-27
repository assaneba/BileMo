<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
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
     * @return string
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
        $successMessage = ['success' => 'Customer deleted !'];

        return $this->json($successMessage);
    }
}
