<?php

namespace Application\Interfaces;

interface RatingsRepository {
    public function getRatingsForProduct(int $productId): array;
}