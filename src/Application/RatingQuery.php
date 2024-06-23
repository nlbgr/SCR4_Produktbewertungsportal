<?php

namespace Application;

class RatingQuery {
    public function __construct(
        public Interfaces\RatingsRepository $ratingsRepository
    ) {}

    public function execute(int $ratingId): ?\Application\RatingData {
        $rating = null;
        $r = $this->ratingsRepository->getRatingById($ratingId);
        if ($r !== null) {
            $rating = new RatingData($r->getId(), $r->getDatetime(), $r->getComment(), $r->getGrade(), $r->getUser(), $r->getProductId());
        }
        return $rating;
    }

}