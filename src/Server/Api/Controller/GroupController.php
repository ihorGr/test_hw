<?php

declare(strict_types=1);

namespace App\Server\Api\Controller;

use App\Server\Api\Controller\Security\ApiKeyAuthenticatedController;
use App\Server\Api\Request\Group\AddGroupRequest;
use App\Server\Api\Request\Group\DeleteGroupRequest;
use App\Server\Api\Request\Group\EditGroupRequest;
use App\Server\Provider\Group\GroupProviderInterface;
use App\Server\Writer\Group\GroupWriterInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractApiController implements ApiKeyAuthenticatedController
{
    /**
     * @Route("/api/get_groups", name="get_groups", methods={"POST"})
     */
    public function getUsers(GroupProviderInterface $groupProvider): Response
    {
        $users = $groupProvider->findAll();

        return new JsonResponse(['data' => $users], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/add_group", name="add_group", methods={"POST"})
     */
    public function addGroup(Request $request, GroupWriterInterface $groupWriter): Response
    {
        $requestData = $this->decodeRequestContent($request);

        if (null === $requestData) {
            return new JsonResponse([], JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        $addGroupRequest = AddGroupRequest::fromRequestData($requestData);

        if (null !== $errorResponse = $this->validate($addGroupRequest)) {
            return $errorResponse;
        }

        try {
            $groupWriter->insert($addGroupRequest);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/edit_group", name="edit_group", methods={"POST"})
     */
    public function editGroup(Request $request, GroupWriterInterface $groupWriter): Response
    {
        $requestData = $this->decodeRequestContent($request);

        if (null === $requestData) {
            return new JsonResponse([], JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        try {
            $editGroupRequest = EditGroupRequest::fromRequestData($requestData);

            if (null !== $errorResponse = $this->validate($editGroupRequest)) {
                return $errorResponse;
            }

            $groupWriter->update($editGroupRequest);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        } catch (ORMException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/delete_group", name="delete_group")
     */
    public function deleteGroup(Request $request, GroupWriterInterface $groupWriter): Response
    {
        $requestData = $this->decodeRequestContent($request);

        if (null === $requestData) {
            return new JsonResponse([], JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        try {
            $deleteGroupRequest = DeleteGroupRequest::fromRequestData($requestData);

            if (null !== $errorResponse = $this->validate($deleteGroupRequest)) {
                return $errorResponse;
            }

            $groupWriter->delete($deleteGroupRequest);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        } catch (ORMException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_OK);
    }

}
