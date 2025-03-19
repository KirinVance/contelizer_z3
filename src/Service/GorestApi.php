<?php

namespace App\Service;

use App\Controller\GorestApiController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GorestApi
{
    private string $apiUrl = 'https://gorest.co.in/public/v2/users';
    private string $apiToken = '8c542206a3ce0fac5099e12f1e0c73e8798e9a90280436b282347b5e03b8a4e0';
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        $this->apiUrl = $_ENV['GOREST_API_URL'] . '/users';
        $this->apiToken = $_ENV['GOREST_API_TOKEN'];
    }

    public function index($limit): array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl, [
            'headers' => $this->getHeaders(),
            'query' => ['per_page' => $limit],
        ]);

        return $response->toArray();
    }

    public function find(string $id): ?array
    {
        $response = $this->httpClient->request('GET', "{$this->apiUrl}/{$id}", [
            'headers' => $this->getHeaders(),
        ]);

        return $response->toArray();
    }

    public function findByNameOrEmail(string $name, string $email): ?array
    {
        $url = $this->buildFindByNameOrEmailUrl($name, $email);

        $response = $this->httpClient->request('GET', $url, [
            'headers' => $this->getHeaders(),
        ]);

        return $response->toArray();
    }

    private function buildFindByNameOrEmailUrl(string $name, string $email): string
    {
        $queryParts = [];

        if ($name !== '') {
            $queryParts[] = "name={$name}";
        }
        if ($email !== '') {
            $queryParts[] = "email={$email}";
        }

        return "{$this->apiUrl}/?" . implode('&', $queryParts);
    }

    public function create(array $userData): array
    {
        $response = $this->httpClient->request('POST', $this->apiUrl, [
            'headers' => $this->getHeaders(),
            'json' => $userData,
        ]);

        return $response->toArray();
    }

    public function update(int $id, array $userData): array
    {
        $response = $this->httpClient->request('PUT', "{$this->apiUrl}/$id", [
            'headers' => $this->getHeaders(),
            'json' => $userData,
        ]);

        return $response->toArray();
    }

    public function delete(int $id): bool
    {
        $response = $this->httpClient->request('DELETE', "{$this->apiUrl}/$id", [
            'headers' => $this->getHeaders(),
        ]);

        return $response->getStatusCode() === 204;
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->apiToken}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
