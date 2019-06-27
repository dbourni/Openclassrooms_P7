<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Exception\ResourceValidationException;
use App\Representation\Users;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationList;

class UserController extends AbstractFOSRestController
{
    /**
     * @param User $user
     *
     * @return User
     *
     * @Rest\Get(
     *     path = "/users/{id}",
     *     name="app_user_show",
     *     requirements = {"id"="\d+"}
     *     )
     *
     * @Rest\View(
     *     populateDefaultVars = false,
     *     serializerGroups = {"detail"},
     *     statusCode = 200
     *     )
     */
    public function showAction(User $user)
    {
        return $user;
    }

    /**
     * @Rest\Get(
     *     path="/users",
     *     name="app_users_list"
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
     *     description="Max number of users per page."
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
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $actualUser = $this->getUser();
        $client = $this->getDoctrine()->getRepository(Client::class)->find($actualUser->getId());

        $pager = $this->getDoctrine()->getRepository(User::class)->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $client
        );

        return new Users($pager);
    }


    /**
     * @param User $user
     * @return mixed
     *
     * @Rest\Post(
     *     path="/users",
     *     name="app_user_create"
     * )
     *
     * @Rest\View(
     *     populateDefaultVars=false,
     *     serializerGroups = {"detail"},
     *     statusCode=201
     * )
     *
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body"
     * )
     */
    public function createAction(User $user, ConstraintViolationList $violations)
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $actualUser = $this->getUser();
        $client = $actualUser->getClient();

        $em = $this->getDoctrine()->getManager();

        $user->setClient($client);
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Rest\Delete(
     *     path = "/users/{id}",
     *     name="app_user_delete",
     *     requirements = {"id"="\d+"}
     *     )
     *
     * @Rest\View(
     *     populateDefaultVars = false,
     *     statusCode = 204
     *     )
     */
    public function deleteAction(User $user)
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
    }
}
