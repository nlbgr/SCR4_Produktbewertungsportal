<?php

namespace Application;

class EditProductCommand {
    const Error_NotAuthenticated = 0x01;
    const Error_ProductDoesNotExist = 0x02;
    const Error_CreateProductFailed = 0x03;

    public function __construct(
        private Interfaces\ProductRepository $productRepository,
        private Interfaces\ManufacturerRepository $manufacturerRepository,
        private Services\AuthenticationService $authenticationService
    ) {}

    public function execute(int $pid, string $pname, string $mname): int {
        $pname = trim($pname);
        $mname = trim($mname);

        $errors = 0;

        $userId = $this->authenticationService->getUserId();
        if ($userId === null) {
            $errors |= self::Error_NotAuthenticated;
            return $errors;
        }

        $product = $this->productRepository->getProductById($pid);
        if ($product === null) {
            $errors |= self::Error_ProductDoesNotExist;
            return $errors;
        }

        $manufacturer = $this->manufacturerRepository->getManufacturerByName($mname);
        if ($manufacturer === null) {
            $manufacturer = $this->manufacturerRepository->createNewManufacturer($mname);
            if ($manufacturer === null) {
                $errors |= self::Error_CreateProductFailed;
                return $errors;
            }
        }

        $prod = $this->productRepository->editProduct($pid, $pname, $userId, $manufacturer->getId());
        if ($prod === null) {
            $errors |= self::Error_CreateProductFailed;
        }

        return $errors;
    }
}