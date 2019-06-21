<?php

namespace App\Controller;

use App\Entity\Product;
use App\Representation\Products;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

class ProductController extends AbstractFOSRestController
{
    /**
     * @param Product $product
     *
     * @return Product
     *
     * @Rest\Get(
     *     path = "/products/{id}",
     *     name="app_product_show",
     *     requirements = {"id"="\d+"}
     *     )
     *
     * @Rest\View(
     *     populateDefaultVars = false,
     *     serializerGroups = {"detail"},
     *     statusCode = 201
     *     )
     */
    public function showAction(Product $product)
    {
        return $product;
    }

    /**
     * @Rest\Get(
     *     path="/products",
     *     name="app_products_list"
     * )
     *
     * @Rest\QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     *
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of movies per page."
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
     *     statusCode = 201
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository('App:Product')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
//            'Phone', 'asc', 5, 1
        );

        //TODO Bug - Avec requirements="[a-zA-Z0-9]" dans le QueryParam de keyword seuls les chiffres sont pris en compte
        return new Products($pager);
//        return $paramFetcher->get('keyword');
    }

    public function createAction()
    {
        //TODO Créer la fonction createAction
    }
}