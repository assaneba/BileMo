<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/api/phones", name="api")
 */
class ProductController extends AbstractController
{
    /**
     * @Rest\Get(
     *     path="/",
     *     name="product_list",
     * )
     * @Rest\View(
     *     statusCode= 200,
     *     serializerGroups={"list"}
     * )
     */
    public function allProducts(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request)
    {
        $query = $productRepository->allProductsQuery();
        $paginatedProducts = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            7
        );

        return $paginatedProducts->getItems();
    }

    /**
     * @param Product $product
     * @return Product
     * @Rest\Get(
     *     path="/{id}",
     *     name="product_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(
     *     statusCode= 200,
     *     serializerGroups={"detail"}
     * )
     */
    public function aProduct(Product $product)
    {
        return $product;
    }
}
