<?php

declare(strict_types=1);

namespace App\Server\Api\Controller;

use App\Serializer\JsonSerializer;
use App\Server\Api\Request\ApiRequestInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var JsonSerializer */
    protected $jsonSerializer;

    public function __construct(
        ValidatorInterface $validator,
        JsonSerializer $jsonSerializer
    ) {
        $this->validator = $validator;
        $this->jsonSerializer = $jsonSerializer;
    }

    protected function decodeRequestContent(Request $request): ?array
    {
        $requestContent = $request->getContent();

        switch ($request->headers->get('content-type')) {
            case 'application/json':
                return $this->jsonSerializer->toArray($requestContent);
            default:
                break;
        }

        return null;
    }

    protected function validate(ApiRequestInterface $apiRequest, array $groups = null): ?Response
    {
        $violationList = $this->validator->validate($apiRequest, $groups);
        if ($violationList->count() > 0) {
            return $this->createErrorJsonResponse(
                $this->getErrors($violationList),
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        return null;
    }

    /**
     * @param array|string $errors
     * @param int $code
     * @return JsonResponse
     */
    protected function createErrorJsonResponse($errors, int $code = JsonResponse::HTTP_OK): JsonResponse
    {
        return new JsonResponse(['errors' => $errors], $code);
    }

    protected function getErrors(ConstraintViolationListInterface $violationList): array
    {
        $errorMessages = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violationList as $violation) {

            $propName = $violation->getPropertyPath();
            if (false === \array_key_exists($propName, $errorMessages)) {
                $errorMessages[$propName] = [];
            }

            $errorMessages[$propName][] = $violation->getMessage();
        }

        return $errorMessages;
    }

}
