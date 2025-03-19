<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\GorestUser;
use App\Form\GorestUserType;
use App\Form\FindGorestUserType;
use App\Repository\GorestUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GorestLocalController extends AbstractController
{
    private const PAGE_SIZE = 10;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GorestUserRepository $repository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    #[Route('/gorest/local/', name: 'gorest.local.index')]
    public function index(): JsonResponse
    {
        $users = $this->repository->findBy([], null, self::PAGE_SIZE);

        return $this->json([
            'success' => true,
            'users' => $users
        ]);
    }

    #[Route('/gorest/local/find', name: 'gorest.local.find')]
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

        $users = $this->repository->findByNameOrEmail($name, $email, self::PAGE_SIZE);
        $count = count($users);
        $plural = ($count === 1) ? '' : 's';

        return $this->json([
            'success' => true,
            'message' => "{$count} user{$plural} found",
            'users' => $users,
        ], 201);
    }

    #[Route('/gorest/local/create', name: 'gorest.local.create')]
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

        $user = new GorestUser();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setGender($data['gender']);
        $user->setStatus($data['status']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    #[Route('/gorest/local/update', name: 'gorest.local.update')]
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

        if (!isset($data['id']) || !$this->isUserDataValid($data)) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $user = $this->repository->find((int)$data['id']);
        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'User does not exist',
            ], 404);
        }

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setGender($data['gender']);
        $user->setStatus($data['status']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ], 201);
    }

    #[Route('/gorest/local/delete', name: 'gorest.local.delete')]
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

        if (!isset($data['id'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $user = $this->repository->find((int)$data['id']);
        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'User does not exist',
            ], 404);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ], 201);
    }

    #[Route('/gorest/sync', name: 'gorest.sync')]
    public function sync(): JsonResponse
    {
        // TODO: load users from API and insert into DB

        if (false) {
            return $this->json([
                'success' => false,
                'message' => 'Synchronisation unsuccessful',
            ], 404);
        }

        return $this->json([
            'success' => true,
            'message' => 'Synchronisation successful',
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

    public function getFindForm(Environment $twig): JsonResponse
    {
        $user = new GorestUser();
        $form = $this->createForm(FindGorestUserType::class, $user);

        $html = $twig->render('gorest_user/find_form.html.twig', [
            'form' => $form->createView(),
        ]);

        return new JsonResponse([
            'success' => true,
            'form' => $html
        ]);
    }

    public function getCreateForm(Environment $twig): JsonResponse
    {
        $user = new GorestUser();
        $form = $this->createForm(GorestUserType::class, $user);

        $html = $twig->render('gorest_user/create_form.html.twig', [
            'form' => $form->createView(),
        ]);

        return new JsonResponse([
            'success' => true,
            'form' => $html
        ]);
    }

    public function getUpdateForm(Request $request, Environment $twig): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);

        if (!isset($data['id'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $user = $this->repository->find((int)$data['id']);
        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'User does not exist',
            ], 404);
        }

        $user = $this->repository->find((int)$data['id']);
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

        if (!isset($data['id'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid data',
            ], 422);
        }

        $user = $this->repository->find((int)$data['id']);
        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'User does not exist',
            ], 404);
        }

        $user = $this->repository->find((int)$data['id']);
        $form = $this->createForm(GorestUserType::class, $user);

        $html = $twig->render('gorest_user/delete_form.html.twig', [
            'form' => $form->createView(),
        ]);

        return new JsonResponse([
            'success' => true,
            'form' => $html
        ]);
    }
}
