<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

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
     * @param ProductRepository $productRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return iterable
     *
     * @Rest\Get(
     *     path="/",
     *     name="product_list",
     * )
     * @Rest\View(
     *     statusCode= 200,
     *     serializerGroups={"list"}
     * )
     */
    public function allProducts(Request $request)
    {
        $cache = new FilesystemAdapter();

        $value = $cache->get('product_list_cache', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            $query = $this->repo->allProductsQuery();

            return $query;
        });

        $paginatedProducts = $this->paginate->paginate(
            $value,
            $request->query->getInt('page', 1),
            7
        );

        return $paginatedProducts->getItems();
    }

    /**
     * @param Product $product
     * @return Product
     *
     * @Rest\Get(
     *     path="/{id}",
     *     name="product_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(statusCode= 200)
     *
     */
    public function aProduct(Product $product)
    {

        return $product;
    }
}
