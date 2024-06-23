<?php

namespace Application\Interfaces;

interface RatingsRepository {
    public function getRatingsForProduct(int $productId): array;
    public function getRatingsChronoForProduct(int $productId): array;
    public function getRatingById(int $ratingId): ?\Application\Entities\Rating;
    public function createRating(int $grade, string $comment, int $userId, int $prodId): ?\Application\Entities\Rating;
    public function editRating(int $ratingId, int $grade, string $comment, int $userId, int $prodId): ?\Application\Entities\Rating;
    public function deleteRating(int $ratingId, int $userId): bool;
}