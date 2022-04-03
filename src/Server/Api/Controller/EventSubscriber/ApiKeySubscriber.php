<?php

declare(strict_types=1);

namespace App\Server\Api\Controller\EventSubscriber;

use App\Serializer\Exception\SerializerException;
use App\Serializer\SerializerInterface;
use App\Server\Api\Controller\Security\ApiKeyAuthenticatedController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiKeySubscriber implements EventSubscriberInterface
{
    private $apiKeys;

    private $serializer;

    public function __construct(array $apiKeys, SerializerInterface $serializer)
    {
        $this->apiKeys = $apiKeys;
        $this->serializer = $serializer;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof ApiKeyAuthenticatedController) {

            $content = $event->getRequest()->getContent();

            try {
                $request = $this->serializer->toArray($content);
            } catch (SerializerException $e) {
                $event->setController(
                    function() use ($e) {
                        return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
                    }
                );
                return;
            }

            $apiKey = $request['api_key'] ?? null;

            if (!in_array($apiKey, $this->apiKeys)) {

                $event->setController(
                    function() {
                        return new JsonResponse(['error' => 'Unauthorized! This action needs a valid api key!'], JsonResponse::HTTP_UNAUTHORIZED);
                    }
                );
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}