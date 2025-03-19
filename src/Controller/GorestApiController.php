<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\GorestUser;
use App\Service\GorestApi;
use App\Form\GorestUserType;
use App\Repository\GorestUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GorestApiController extends AbstractController
{
    public const PAGE_SIZE = 10;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GorestUserRepository $repository,
        private GorestApi $api,
    ) {
    }

    #[Route('/gorest/api/', name: 'gorest.api.index')]
    public function index(): JsonResponse
    {
        $users = $this->api->index(self::PAGE_SIZE);

        return $this->json([
            'success' => true,
            'users' => $users
        ]);
    }

    #[Route('/gorest/api/find', name: 'gorest.api.find')]
    public function find(Request $request): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);

        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';

        if ($name === '' && $email === '') {
            return $this->json([
                'success' => false,
                'message' => 'No criteria specified',
            ], 422);
        }

        $users = $this->api->findByNameOrEmail($name, $email);
        $count = count($users);
        $plural = ($count === 1) ? '' : 's';

        return $this->json([
            'success' => true,
            'message' => "{$count} user{$plural} found",
            'users' => $users,
        ], 201);
    }

    #[Route('/gorest/api/create', name: 'gorest.api.create')]
    public function create(Request $request, CsrfTokenManagerInterface $csrfTokenManager): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);

        $csrfToken = $data['_token'] ?? '';
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('gorest_user_form', $csrfToken))) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Invalid CSRF token'
            ], 403);
        }

        if (!$this->isUserDataValid($data)) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $result = $this->api->create($data);

        return $this->json([
            'success' => $result,
            'message' => ($result) ? 'User created successfully' : 'Create user error',
        ], 201);
    }

    #[Route('/gorest/api/update', name: 'gorest.api.update')]
    public function update(Request $request, CsrfTokenManagerInterface $csrfTokenManager): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);

        $csrfToken = $data['_token'] ?? '';
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('gorest_user_form', $csrfToken))) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Invalid CSRF token'
            ], 403);
        }

        if (!isset($data['gorestId']) || !$this->isUserDataValid($data)) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $result = $this->api->update((int)$data['gorestId'], $data);

        return $this->json([
            'success' => $result,
            'message' => ($result) ? 'User updated successfully': 'Update user error',
        ], 201);
    }

    #[Route('/gorest/api/delete', name: 'gorest.api.delete')]
    public function delete(Request $request, CsrfTokenManagerInterface $csrfTokenManager): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);

        $csrfToken = $data['_token'] ?? '';
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('gorest_user_form', $csrfToken))) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Invalid CSRF token'
            ], 403);
        }

        if (!isset($data['gorestId'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $result = $this->api->delete((int)$data['gorestId']);

        return $this->json([
            'success' => $result,
            'message' => ($result) ? 'User deleted successfully' : 'Delete user error',
        ], 201);
    }

    private function isUserDataValid(array $data): bool
    {
        if (!isset($data['name']) || !is_string($data['name'])) {
            return false;
        }

        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (!isset($data['gender']) || !is_string($data['gender'])) {
            return false;
        }

        if (!isset($data['status']) || !is_string($data['status'])) {
            return false;
        }

        return true;
    }

    public function getUpdateForm(Request $request, Environment $twig): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);

        if (!isset($data['gorestId'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $apiUser = $this->api->find((int)$data['gorestId']);
        if (!$apiUser) {
            return $this->json([
                'success' => false,
                'message' => 'User does not exist',
            ], 404);
        }

        $user = new GorestUser();
        $user->setGorestId($apiUser['id']);
        $user->setName($apiUser['name']);
        $user->setEmail($apiUser['email']);
        $user->setGender($apiUser['gender']);
        $user->setStatus($apiUser['status']);

        $form = $this->createForm(GorestUserType::class, $user);

        $html = $twig->render('gorest_user/update_form.html.twig', [
            'form' => $form->createView(),
        ]);

        return new JsonResponse([
            'success' => true,
            'form' => $html
        ]);
    }

    public function getDeleteForm(Request $request, Environment $twig): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);

        if (!isset($data['gorestId'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $apiUser = $this->api->find((int)$data['gorestId']);
        if (!$apiUser) {
            return $this->json([
                'success' => false,
                'message' => 'User does not exist',
            ], 404);
        }

        $user = new GorestUser();
        $user->setGorestId($apiUser['id']);
        $user->setName($apiUser['name']);
        $user->setEmail($apiUser['email']);
        $user->setGender($apiUser['gender']);
        $user->setStatus($apiUser['status']);

        $form = $this->createForm(GorestUserType::class, $user);

        $html = $twig->render('gorest_user/delete_form.html.twig', [
            'form' => $form->createView(),
        ]);

        return new JsonResponse([
            'success' => true,
            'form' => $html
        ]);
    }

    #[Route('/gorest/sync', name: 'gorest.sync')]
    public function syncLocalWithApi(): JsonResponse
    {
        $users = $this->api->index(self::PAGE_SIZE);
        if (!$users) {
            return $this->json([
                'success' => false,
                'message' => 'Synchronisation unsuccessful',
            ], 404);
        }

        $this->repository->syncLocalToArray($users);

        return $this->json([
            'success' => true,
            'message' => 'Synchronisation successful',
        ], 201);
    }
}
