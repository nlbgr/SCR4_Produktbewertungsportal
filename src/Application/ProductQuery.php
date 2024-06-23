<?php

namespace Application;

use Application\Entities\Product;

class ProductQuery {
    public function __construct(
        private Interfaces\ProductRepository $productRepository,
        private RatingsQuery $ratingsQuery
    ){}

    public function execute(int $productId): ?\Application\ProductData {
        $product = null;
        $p = $this->productRepository->getProductById($productId);
        if ($p !== null) {
            $ratings = $this->ratingsQuery->execute($p->getId());
            $meanR = 0;
            foreach ($ratings as $r) {
                $meanR += $r->grade;
            };
            $meanR = $meanR / count($ratings);

            $product = new ProductData($p->getId(), $p->getName(), $p->getUserName(), $p->getManufacturerName(), count($ratings), $meanR);
        }
        return $product;
    }
}