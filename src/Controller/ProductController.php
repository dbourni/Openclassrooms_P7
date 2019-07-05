<?php

namespace App\Controller;

use App\Entity\Product;
use App\Representation\Products;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;


/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends AbstractFOSRestController
{
    /**
     * Detail of a particular product
     *
     * @param Product $product
     *
     * @return Product
     *
     * @Rest\Get(
     *     path = "/products/{id}",
     *     name="app_product_show",
     *     requirements = {"id"="\d+"},
     *     )
     *
     * @Rest\View(
     *     populateDefaultVars = false,
     *     serializerGroups = {"detail"},
     *     statusCode = 200,
     *     )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Detail of a particular product",
     *     @Model(type=Product::class, groups={"detail"})
     * )
     *
     * @Security(name="Bearer")
     */
    public function showAction(Product $product)
    {
        return $product;
    }

    /**
     * List the products
     *
     * @Rest\Get(
     *     path="/products",
     *     name="app_products_list"
     * )
     *
     * @Rest\QueryParam(
     *     name="keyword",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     *
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of products per page."
     * )
     *
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     *
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     *
     * @Rest\View(populateDefaultVars = false,
     *     serializerGroups = {"list"},
     *     statusCode = 200
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="List of the products",
     *     @Model(type=Product::class, groups={"list"})
     * )
     *
     * @Security(name="Bearer")
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository(Product::class)->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Products($pager);
    }
}