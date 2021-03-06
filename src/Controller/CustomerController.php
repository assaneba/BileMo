<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Service\CacheDeleter;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Swagger\Annotations as OA;

/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/api/customers")
 */
class CustomerController extends AbstractController
{
    private $repo;
    private $paginator;

    public function __construct(CustomerRepository $customerRepository, PaginatorInterface $paginator)
    {
        $this->repo      = $customerRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     * @param CacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @Rest\Get(
     *     path="/",
     *     name="customers_list"
     * )
     * @Rest\View(statusCode= 200)
     *
     * @OA\Get(
     *     @OA\Response(response="200", description="List of customers")
     * )
     *
     */
    public function allCustomers(Request $request, CacheInterface $cache)
    {
        $page = $request->query->getInt('page', 1);

        $cachedVal = $cache->get('customers_list'.$page, function (ItemInterface $item)
                                 use ($page) {
                        $item->expiresAfter(3600);

                        $query = $this->repo->allCustomersQuery($this->getUser());

                        return  $this->paginator->paginate(
                                $query,
                                $page,
                                10
                                );
                    });

        return $cachedVal->getItems();
    }

    /**
     * @param $id
     * @param CacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @Rest\Get(
     *     path="/{id}",
     *     name="customer_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(statusCode= 200)
     *
     * @Security("is_granted('ROLE_USER') && customer.getUser() == user")
     *
     * @OA\Get(
     *     @OA\Response(response="200", description="Return a specific customer details")
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     type="number",
     *     description="The id of the customer"
     * )
     */
    public function aCustomer($id, CacheInterface $cache, Customer $customer)
    {
        return $cache->get('customer'.$id, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(3600);

            return $this->repo->find($id);
        });
    }

    /**
     * @param Customer $customer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param CacheInterface $cache
     * @return Customer
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @Rest\Post(
     *     path="/",
     *     name="customer_add"
     * )
     * @Rest\View(statusCode= 201)
     * @ParamConverter("customer", converter="fos_rest.request_body")
     *
     * @OA\Post(
     *     @OA\Response(response="201", description="Return a new customer")
     * )
     */
    public function addCustomer(Customer $customer, EntityManagerInterface $manager, ValidatorInterface $validator,
                                CacheInterface $cache, CacheDeleter $cacheDeleter)
    {
        $errors = $validator->validate($customer);
        if(count($errors)) {
            throw new \Exception($errors);
        }
        $customer->setUser($this->getUser());

        $manager->persist($customer);
        $manager->flush();

        $cacheDeleter->deleteCache($cache);

        return $customer;
    }

    /**
     * @param Customer $customer
     * @param EntityManagerInterface $manager
     * @param CacheInterface $cache
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @Rest\Delete(
     *     path="/{id}",
     *     name="customer_delete"
     * )
     * @Rest\View(statusCode= 204)
     *
     * @Security("is_granted('ROLE_USER') && customer.getUser() == user")
     *
     * @OA\Delete(
     *     @OA\Response(response="204", description="Delete a specific customer")
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     type="number",
     *     description="The id of the customer"
     * )
     */
    public function deleteCustomer(Customer $customer, EntityManagerInterface $manager, CacheInterface $cache, CacheDeleter $cacheDeleter)
    {
        $cacheDeleter->deleteCache($cache, $customer->getId());

        $manager->remove($customer);
        $manager->flush();
    }

    /**
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param Request $request
     * @param CacheInterface $cache
     * @return object|null
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @Rest\Put(
     *     path="/{id}",
     *     name="customer_update",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(statusCode= 200)
     *
     * @OA\Put(
     *     @OA\Response(response="200", description="Update a specific customer")
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     type="number",
     *     description="The id of the customer"
     * )
     */
    public function updateCustomer(EntityManagerInterface $manager, ValidatorInterface $validator,
                                   Request $request, CacheInterface $cache, CacheDeleter $cacheDeleter)
    {
        $customerUpdater = $manager->getRepository(Customer::class)->find($request->get('id'));

        // Check if some values have changed and set them in existing object
        foreach($request->request->all() as $key => $value ) {
            if($key && !empty($value)) {
                $keyPiece = explode('_', $key);
                foreach ($keyPiece as $index => $piece) {
                    $keyPiece[$index] = ucfirst($piece);
                }
                $setter = 'set'.implode($keyPiece);
                $customerUpdater->$setter($value);
            }
        }
        $errors = $validator->validate($customerUpdater);
        if (count($errors)) {
            throw new \Exception('Invalid argument(s) detected');
        }
        $cacheDeleter->deleteCache($cache, $customerUpdater->getId());
        $manager->flush();

        return $customerUpdater;
    }
}
