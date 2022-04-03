<?php

declare(strict_types=1);

namespace App\Client\InternalClient;

use App\Client\InternalClient\Response\RemoteResponse;
use App\Client\Request\ClientApiRequestInterface;
use App\Serializer\SerializerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use App\Client\InternalClient\Exception\InternalApiException;

class ServerInternalClient implements ServerInternalClientInterface
{
    private const HOST = 'localhost';

    private const DELETE_GROUP_ENDPOINT = '/api/delete_group';

    /** @var HttpClientInterface */
    protected $connection;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var string */
    protected $apiKey;

    public function __construct(array $apiKeys, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->apiKey = $apiKeys;
    }

    public function addGroup(ClientApiRequestInterface $request): RemoteResponse
    {
        //TODO: implement
    }

    public function editGroup(ClientApiRequestInterface $request): RemoteResponse
    {
        //TODO: implement
    }

    public function deleteGroup(ClientApiRequestInterface $request): RemoteResponse
    {
        $apiUrl = $this->createApiUrl(self::DELETE_GROUP_ENDPOINT);

        return $this->processRequest($apiUrl, $request);
    }

    public function addUser(ClientApiRequestInterface $request): RemoteResponse
    {
        //TODO: implement
    }

    /**
     * @param ClientApiRequestInterface $request
     * @return RemoteResponse
     * @throws InternalApiException
     */
    protected function processRequest($apiUrl, ClientApiRequestInterface $request): RemoteResponse
    {
        $body = $request->getRequestData($this->apiKey);

        $response =  $this->sendRequest($apiUrl, $body);

        try {
            $responseBody = $response->getContent(false);
        } catch (HttpException $exception) {
            throw InternalApiException::fromPreviousException($exception);
        }

        $remoteArr = $this->serializer->toArray($responseBody);

        return new RemoteResponse($remoteArr);
    }

    /**
     * @param string $apiUrl
     * @param array $body
     * @return ResponseInterface
     * @throws InternalApiException
     */
    protected function sendRequest(string $apiUrl, array $body): ResponseInterface
    {


        try {
            $response = $this->createConnection()->request('POST', $apiUrl, [
                'json' => $body
            ]);

            $responseStatus = $response->getStatusCode();
        } catch (TransportExceptionInterface $exception) {
            throw InternalApiException::fromPreviousException($exception);
        }

        if ($responseStatus >= 400) {
            throw InternalApiException::fromStatusCode($responseStatus);
        }

        return $response;
    }

    protected function createApiUrl(string $endpoint, array $options = []) : string
    {
        if ([] !== $options) {
            $query = ''; //TODO: implement
        }

        return sprintf('%s/%s', self::HOST, $endpoint);
    }

    protected function createConnection() : HttpClientInterface
    {
        if (null === $this->connection) {
            $this->connection = HttpClient::create();
        }

        return $this->connection;
    }
}
