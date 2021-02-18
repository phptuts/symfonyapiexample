<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\Type\UserType;
use App\Model\ResponseModel;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

class UserController extends AbstractFOSRestController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post (
     *     description="This will create a user with optional settings",
     *     @OA\RequestBody(@OA\JsonContent(ref=@Model(type=UserType::class))),
     *     @OA\Response (description="Returns the user", response="201")
     * )
     *
     * @param Request $request
     * @Rest\Post("/api/users")
     * @Rest\View(statusCode=201)
     * @return User|FormInterface
     */
    public function post(Request $request)
    {
        return $this->processUserRequest($request, new User());
    }

    /**
     * @OA\Delete   (
     *     description="This will delete a user and their settings",
     *     @OA\Response (description="Delete a user", response="204")
     * )
     * @param User $user
     * @Rest\Delete("/api/users/{id}")
     * @Rest\View(statusCode=204)
     *
     * @return null
     */
    public function deleteUser(User $user) {
        $this->userService->delete($user);

        return [];
    }

    /**
     * @param Request $request
     * @param User $user
     * @OA\Put  (
     *     description="This will edit a user and their settings.  You must submit all their setting otherwise it will delete the them.",
     *     @OA\RequestBody(@OA\JsonContent(ref=@Model(type=UserType::class))),
     *     @OA\Response (description="Returns the user", response="200")
     * )
     * @Rest\Put("/api/users/{id}")
     * @Rest\View(statusCode=200)
     *
     * @return User|FormInterface
     */
    public function put(Request $request, User $user)
    {
        return $this->processUserRequest($request, $user);
    }


    /**
     * @OA\Get(
     *     description="This will get a list of users.",
     *     @OA\Response(description="Returns a list of user", response="200")
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="This will fetch a group of ten users at a certain point.  Page numbers start at 1.",
     *     @OA\Schema(type="string")
     * )
     * @param Request $request
     * @Rest\Get("/api/users")
     * @Rest\View(statusCode=200)
     *
     * @return array
     */
    public function getUsers(Request $request) {
        $page = $request->query->get('page') ?? 1;
        $response = $this->userService->getPaginatedResponse(intval($page));
        return $response->toArray();
    }

    /**
     * @OA\Get(
     *     description="This will get one user.",
     *     @OA\Response (description="Returns the user", response="200")
     * )
     * @param User $user
     * @Rest\Get("/api/users/{id}")
     * @Rest\View(statusCode=200)
     *
     * @return array
     */
    public function getSingleUser(User $user) {
        $response = new ResponseModel($user, ResponseModel::USER_RESPONSE_TYPE);

        return $response->toArray();
    }

    /**
     * This is a helper method for processing the user create and update request
     *
     * @param Request $request
     * @param User $user
     * @return mixed|FormInterface
     */
    protected function processUserRequest(Request  $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->userService->save($user);
            $response = new ResponseModel($user, ResponseModel::USER_RESPONSE_TYPE);
            return $response->toArray();
        }

        return $form;
    }

}