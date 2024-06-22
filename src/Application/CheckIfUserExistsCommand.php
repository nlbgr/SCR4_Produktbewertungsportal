<?php

namespace Application;

class CheckIfUserExistsCommand {
    public function __construct(
        private Interfaces\UserRepository $userRepository
    ){ }

    public function execute(string $userName): bool {
        return $this->userRepository->getUserForUserName($userName) !== null;
    }
}