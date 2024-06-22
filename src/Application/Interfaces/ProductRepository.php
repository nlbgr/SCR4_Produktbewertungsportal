<?php

namespace Application\Interfaces;

interface ProductRepository {
    public function getProducts(): array;
    public function getBooksForFilter(string $filter): array;
}