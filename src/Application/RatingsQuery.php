<?php

namespace Application;

class RatingsQuery {
    public function __construct(
        private Interfaces\RatingsRepository $ratingsRepository,
    ){}

    public function execute(int $pId): array {
        $res = [];
        foreach ($this->ratingsRepository->getRatingsForProduct($pId) as $r) {
            $res[] = new RatingData($r->getId(), $r->getDatetime(), $r->getComment(), $r->getGrade(), $r->getUser(), $r->getProductId());
        }
        return $res;
    }
}