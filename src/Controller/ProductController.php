<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Swagger\Annotations as OA;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/api/products")
 */
class ProductController extends AbstractController
{
    private $repo;
    private $paginate;

    public function __construct(ProductRepository $productRepository, PaginatorInterface $paginator)
    {
        $this->repo     = $productRepository;
        $this->paginate = $paginator;
    }

    /**
     * @param CacheInterface $cache
     * @param Request $request
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @Rest\Get(
     *     path="/",
     *     name="product_list"
     * )
     * @Rest\View(
     *     statusCode= 200,
     *     serializerGroups={"list"}
     * )
     *
     * @OA\Get(
     *     @OA\Response(response="200", description="Return a list of products")
     * )
     */
    public function allProducts(CacheInterface $cache, Request $request)
    {
        $page = $request->query->getInt('page', 1);

        $value = $cache->get('product_list'.$page, function (ItemInterface $item)
                             use ($page) {
            $item->expiresAfter(3600);

            $query = $this->repo->allProductsQuery();

            return  $paginatedProducts = $this->paginate->paginate(
                    $query,
                    $page,
                    7
                    );
        });

        return $value->getItems();
    }

    /**
     * @param $id
     * @param CacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @Rest\Get(
     *     path="/{id}",
     *     name="product_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(statusCode= 200)
     *
     * @OA\Get(
     *     @OA\Response(response="200", description="Return a specific product details")
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     type="number",
     *     description="The id of the product"
     * )
     */
    public function aProduct($id, CacheInterface $cache)
    {
        return $cache->get('product'.$id, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(3600);

            return $this->repo->find($id);
        });
    }
}
