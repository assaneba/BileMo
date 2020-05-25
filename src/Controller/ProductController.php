<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     *     name="product_list"
     * )
     * @Rest\View(
     *     statusCode= 200,
     *     serializerGroups={"list"}
     * )
     */
    public function allProducts(ProductRepository $productRepository)
    {
        $phones = $productRepository->findAll();

        return $phones;
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
