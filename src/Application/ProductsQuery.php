<?php

namespace Application;

class ProductsQuery {
    public function __construct(
        private Interfaces\ProductRepository $productRepository,
        private RatingsQuery $ratingsQuery
    ){}

    public function execute(): array {
        $res = [];
        foreach ($this->productRepository->getProducts() as $p) {
            $ratings = $this->ratingsQuery->execute($p->getId());
            $meanR = 0;
            foreach ($ratings as $r) {
                $meanR += $r->grade;
            };
            if (count($ratings) !== 0) {
                $meanR = $meanR / count($ratings);
            }
            $res[] = new ProductData($p->getId(), $p->getName(), $p->getUserName(), $p->getManufacturerName(), count($ratings), $meanR);
        }
        return $res;
    }
}