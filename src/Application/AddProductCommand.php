<?php

namespace Application;

class AddProductCommand {
    const Error_NotAuthenticated = 0x01;
    const Error_ProductAlreadyExists = 0x02;
    const Error_CreateProductFailed = 0x03;

    public function __construct(
        private Interfaces\ProductRepository $productRepository,
        private Interfaces\ManufacturerRepository $manufacturerRepository,
        private Services\AuthenticationService $authenticationService
    ) { }

    public function execute(string $pname, string $mname): int {
        $pname = trim($pname);
        $mname = trim($mname);

        $errors = 0;

        $userId = $this->authenticationService->getUserId();
        if ($userId === null) {
            $errors |= self::Error_NotAuthenticated;
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

        $prod = $this->productRepository->getProductByNameAndManufacturer($pname, $manufacturer->getId());
        if ($prod !== null) {
            $errors |= self::Error_ProductAlreadyExists;
        }

        if (!$errors) {
            $prod = $this->productRepository->createProduct($pname, $userId, $manufacturer->getId());
            if ($prod === null) {
                $errors |= self::Error_CreateProductFailed;
            }
        }

        return $errors;
    }


}