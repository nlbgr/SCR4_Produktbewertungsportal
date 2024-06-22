<?php

namespace Application;

readonly class RatingData {
    function __construct(
        public int $id,
        public string $datetime,
        public string $comment,
        public int $grade,
        public string $user,
        public int $productId
    ) { }
}