<?php

namespace Application;

class CheckIfUserExistsQuery {
    public function __construct(
        private Interfaces\UserRepository $userRepository
    ){ }

    public function execute(string $userName): bool {
        return $this->userRepository->getUserForUserName($userName) !== null;
    }
}