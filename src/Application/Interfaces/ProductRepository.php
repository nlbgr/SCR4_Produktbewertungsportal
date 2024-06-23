<?php

namespace Application\Interfaces;

interface ProductRepository {
    public function getProducts(): array;
    public function getProductsForFilter(string $filter): array;
    public function getProductById(int $productId): ?\Application\Entities\Product;
    public function getProductByNameAndManufacturer(string $pname, string $manId): ?\Application\Entities\Product;
    public function createProduct(string $pname, int $userId, int $manId): ?\Application\Entities\Product;
    public function editProduct(int $pid, string $pname, int $userId, int $manId): ?\Application\Entities\Product;
    public function deleteProduct(string $productId, int $userId): bool;
}