<?php
namespace Presentation\Controllers;

class Products extends \Presentation\MVC\Controller {
    const PARAM_PRODUCT_ID = 'pid';
    const PARAM_FILTER = 'f';

    public function __construct(
        private \Application\ProductsQuery          $productsQuery,
        private \Application\BookSearchQuery        $bookSearchQuery,
        private \Application\SignedInUserQuery      $signedInUserQuery,
        private \Application\RatingsChronoQuery     $ratingsChronoQuery,
        private \Application\ProductQuery           $productQuery
    ) { }

    public function GET_Index(): \Presentation\MVC\ActionResult {
        return $this->view('productList', [
            'user' => $this->signedInUserQuery->execute(),
            'products' => $this->productsQuery->execute(),
            'context' => $this->getRequestUri()
        ]);
    }

    public function GET_Search(): \Presentation\MVC\ActionResult {
        echo $this->tryGetParam(self::PARAM_FILTER, $value) ? $value : 'nothing';
        echo ' | ', $this->tryGetParam(self::PARAM_FILTER, $value);
        return $this->view('productSearch', [
            'user' => $this->signedInUserQuery->execute(),
            'filter' => $this->tryGetParam(self::PARAM_FILTER, $value) ? $value : '',
            'books' => $this->tryGetParam(self::PARAM_FILTER, $value) ? $this->bookSearchQuery->execute($value) : null,
            'context' => $this->getRequestUri()
        ]);
    }

    public function GET_Details(): \Presentation\MVC\ActionResult {
        $b = $this->tryGetParam(self::PARAM_PRODUCT_ID, $value);
        $p = $this->productQuery->execute($value);
        if (!$b || $p === null) {
            // redirect to products page in an error case
            return $this->redirect('Products', 'Index');
        }

        return $this->view('productDetails', [
            'user' => $this->signedInUserQuery->execute(),
            'product' => $p,
            'ratings' => $this->ratingsChronoQuery->execute($value),
            'context' => $this->getRequestUri()
        ]);
    }
}