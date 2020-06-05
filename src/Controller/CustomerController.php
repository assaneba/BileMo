<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\ArrayTransformerInterface;
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
     */
    public function allCustomers(Request $request, CacheInterface $cache)
    {
        $page = $request->query->getInt('page', 1);

        $cachedVal = $cache->get('customers_list'.$request->query->get('page'), function (ItemInterface $item)
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
     *
     * @Security("is_granted('ROLE_USER') && customer.getUser() == user")
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
     *
     * @Security("is_granted('ROLE_USER') && customer.getUser() == user")
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
