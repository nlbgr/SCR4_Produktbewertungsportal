<?php

namespace Application\Interfaces;

interface ProductRepository {
    public function getProducts(): array;
    public function getProductsForFilter(string $filter): array;
    public function getProductById(int $productId): ?\Application\Entities\Product;
}