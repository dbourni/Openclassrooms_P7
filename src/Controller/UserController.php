<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Exception\ResourceValidationException;
use App\Representation\Users;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolationList;

class UserController extends AbstractFOSRestController
{
    /**
     * Detail of a particular user
     *
     * @param User $user
     *
     * @return User
     *
     * @Rest\Get(
     *     path = "/users/{id}",
     *     name="app_user_show",
     *     requirements = {"id"="\d+"}
     * )
     *
     * @Rest\View(
     *     populateDefaultVars = false,
     *     serializerGroups = {"detail"},
     *     statusCode = 200
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Detail of a particular user",
     *     @Model(type=User::class, groups={"detail"})
     * )
     *
     * @Security(name="Bearer")
     */
    public function showAction(User $user)
    {
        return $user;
    }

    /**
     * List the users
     *
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="List of users",
     *     @Model(type=User::class, groups={"list"})
     * )
     *
     * @Security(name="Bearer")
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
     * Create a user
     *
     * @param User $user
     * @return mixed
     * @throws ResourceValidationException
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
     *
     * @Rest\RequestParam(
     *     name = "email",
     *     nullable=false,
     *     description="User email"
     * )
     *
     * @Rest\RequestParam(
     *     name = "name",
     *     nullable=false,
     *     description="User name"
     * )
     *
     * @Rest\RequestParam(
     *     name = "password",
     *     nullable=false,
     *     description="User password"
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Detail of the created user",
     *     @Model(type=User::class, groups={"detail"})
     * )
     *
     * @Security(name="Bearer")
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
     * Delete a user
     *
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
     *
     * @SWG\Response(
     *     response=204,
     *     description="Nothing",
     * )
     *
     * @Security(name="Bearer")
     */
    public function deleteAction(User $user)
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
    }
}
