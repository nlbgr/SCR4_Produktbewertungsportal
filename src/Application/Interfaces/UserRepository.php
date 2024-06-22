<?php

namespace Application\Interfaces;

interface UserRepository {
    public function getUser(int $id): ?\Application\Entities\User;
    public function getUserForUserName(string $userName): ?\Application\Entities\User;
    public function createUser(string $userName, string $password): ?\Application\Entities\User;
}