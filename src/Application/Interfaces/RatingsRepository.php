<?php

namespace Application\Interfaces;

interface RatingsRepository {
    public function getRatingsForProduct(int $productId): array;
    public function getRatingsChronoForProduct(int $productId): array;
}