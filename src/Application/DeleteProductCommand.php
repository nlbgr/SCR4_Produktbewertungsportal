<?php

namespace Application;

class DeleteProductCommand {
    const Error_NotAuthenticated = 0x01;
    const Error_DeleteProductFailed = 0x02;

    public function __construct(
        private Interfaces\ProductRepository $productRepository,
        private Services\AuthenticationService $authenticationService
    ) { }

    public function execute(int $productId): int {
        $errors = 0;

        $userId = $this->authenticationService->getUserId();
        if ($userId === null) {
            $errors |= self::Error_NotAuthenticated;
            return $errors;
        }

        $result = $this->productRepository->deleteProduct($productId, $userId);
        if (!$result) {
            $errors |= self::Error_DeleteProductFailed;
        }

        return $errors;
    }
}