<?php

declare(strict_types=1);

namespace App\User\Application\LoginUser;

use App\User\Domain\LoginUserException;
use App\User\Domain\LoginUserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/login', methods: Request::METHOD_POST)]
class LoginUserController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly LoginUserService $service,
        private readonly JWTTokenManagerInterface $JWTTokenManager,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        try {
            $user = $this->service->execute(
                $request->get('email', ''),
                $request->get('password', ''),
            );
        } catch (LoginUserException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }

        return new JsonResponse([
            'token' => $this->JWTTokenManager->create($user),
        ]);
    }
}
