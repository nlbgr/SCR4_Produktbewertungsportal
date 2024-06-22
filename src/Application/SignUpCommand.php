<?php

namespace Application;

class SignUpCommand {
    public function __construct(
        private Services\AuthenticationService $authenticationService,
        private Interfaces\UserRepository $userRepository
    ){ }

    public function execute(string $userName, string $password): bool {
        $this->authenticationService->signOut();
        $user = $this->userRepository->createUser($userName, $password);

        if ($user != null) {
            $this->authenticationService->signIn($user->getId());
            return true;
        }

        return false;
    }

}