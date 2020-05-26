<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/api/customers")
 */
class CustomerController extends AbstractController
{
    /**
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
}
