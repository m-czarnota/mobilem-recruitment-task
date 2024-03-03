<?php

declare(strict_types=1);

namespace App\User\Domain;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class CreateFirstUserService
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    /**
     * @throws FirstUserAlreadyExistException
     */
    public function execute(): User
    {
        $defaultEmail = $this->parameterBag->get('user.default.email');
        $defaultPlainPassword = $this->parameterBag->get('user.default.password');

        if ($existedUser = $this->userRepository->findOneByEmail($defaultEmail)) {
            throw new FirstUserAlreadyExistException("Already exist first user with ID {$existedUser->id}");
        }

        $user = new User(
            null,
            $defaultEmail,
            [UserRoleEnum::USER->value],
        );
        $user->setPassword($this->userPasswordHasher->hash($user, $defaultPlainPassword));

        $this->userRepository->add($user);

        return $user;
    }
}
