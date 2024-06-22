<?php

namespace Application;

readonly class ProductData {
    public function __construct(
        public int $id,
        public string $name,
        public string $user,
        public string $manufacturer,
        public int $numOfRatings,
        public float $meanRating
    ) { }
}