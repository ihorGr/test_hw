<?php

declare(strict_types=1);

namespace App\Server\Api\Controller;

use App\Server\Api\Controller\Security\ApiKeyAuthenticatedController;
use App\Server\Api\Request\User\AddUserGroupRequest;
use App\Server\Api\Request\User\AddUserRequest;
use App\Server\Api\Request\User\DeleteUserGroupRequest;
use App\Server\Api\Request\User\DeleteUserRequest;
use App\Server\Api\Request\User\EditUserRequest;
use App\Server\Provider\User\UserProviderInterface;
use App\Server\Writer\User\UserWriterInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractApiController  implements ApiKeyAuthenticatedController
{
    /**
     * @Route("/api/get_users", name="get_users", methods={"POST"})
     */
    public function getUsers(UserProviderInterface $userProvider): Response
    {
        $users = $userProvider->findAll();

        return new JsonResponse(['data' => $users], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/get_users_by_group/{groupId}", name="get_users_by_group", methods={"POST"})
     */
    public function getUsersByGroup(int $groupId, UserProviderInterface $userProvider): Response
    {
        if (false === is_int($groupId)) {
            return new JsonResponse(['error' => 'Invalid group id'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $users = $userProvider->findByGroupId($groupId);

        return new JsonResponse(['data' => $users], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/add_user", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request, UserWriterInterface $userWriter): Response
    {
        $requestData = $this->decodeRequestContent($request);

        if (null === $requestData) {
            return new JsonResponse([], JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        try {
            $addUserRequest = AddUserRequest::fromRequestData($requestData);

            if (null !== $errorResponse = $this->validate($addUserRequest)) {
                return $errorResponse;
            }

            $userWriter->insert($addUserRequest);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['errors' => $e->getMessage()]);
        } catch (ORMException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/edit_user", name="edit_user", methods={"POST"})
     */
    public function editUser(Request $request, UserWriterInterface $userWriter): Response
    {
        $requestData = $this->decodeRequestContent($request);

        if (null === $requestData) {
            return new JsonResponse([], JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        try {
            $editUserRequest = EditUserRequest::fromRequestData($requestData);

            if (null !== $errorResponse = $this->validate($editUserRequest)) {
                return $errorResponse;
            }

            $userWriter->update($editUserRequest);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        } catch (ORMException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/delete_user", name="delete_user", methods={"POST"})
     */
    public function deleteUser(Request $request, UserWriterInterface $userWriter): Response
    {
        $requestData = $this->decodeRequestContent($request);

        if (null === $requestData) {
            return new JsonResponse([], JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        try {
            $deleteUserRequest = DeleteUserRequest::fromRequestData($requestData);

            if (null !== $errorResponse = $this->validate($deleteUserRequest)) {
                return $errorResponse;
            }

            $userWriter->delete($deleteUserRequest);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        } catch (ORMException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/add_user_group", name="add_user_group", methods={"POST"})
     */
    public function addUserGroup(Request $request, UserWriterInterface $userWriter)
    {
        $requestData = $this->decodeRequestContent($request);

        if (null === $requestData) {
            return new JsonResponse([], JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        try {
            $addUserGroupRequest = AddUserGroupRequest::fromRequestData($requestData);

            if (null !== $errorResponse = $this->validate($addUserGroupRequest)) {
                return $errorResponse;
            }

            $userWriter->addUserGroup($addUserGroupRequest);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        } catch (ORMException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/delete_user_group", name="delete_user_group", methods={"POST"})
     */
    public function deleteUserGroup(Request $request, UserWriterInterface $userWriter)
    {
        $requestData = $this->decodeRequestContent($request);

        if (null === $requestData) {
            return new JsonResponse([], JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        try {
            $deleteUserGroupRequest = DeleteUserGroupRequest::fromRequestData($requestData);

            if (null !== $errorResponse = $this->validate($deleteUserGroupRequest)) {
                return $errorResponse;
            }

            $userWriter->deleteUserGroup($deleteUserGroupRequest);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        } catch (ORMException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_OK);
    }
}