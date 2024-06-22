<?php
namespace Presentation\Controllers;

class Products extends \Presentation\MVC\Controller {
    const PARAM_CATEGORY_ID = 'cid';
    const PARAM_FILTER = 'f';

    public function __construct(
        private \Application\ProductsQuery     $booksQuery,
        private \Application\BookSearchQuery   $bookSearchQuery,
        private \Application\SignedInUserQuery $signedInUserQuery
    ) {

    }

    public function GET_Index(): \Presentation\MVC\ActionResult {
        return $this->view('productList', [
            'user' => $this->signedInUserQuery->execute(),
            'products' => $this->booksQuery->execute(),
            'context' => $this->getRequestUri()
        ]);

        return $this->view('productList', [
            'user' => $this->signedInUserQuery->execute(),
            'categories' => $this->categoriesQuery->execute(),
            'selectedCategoryId' => $this->tryGetParam(self::PARAM_CATEGORY_ID, $value) ? $value : null,
            'books' => $this->tryGetParam(self::PARAM_CATEGORY_ID, $value) ? $this->booksQuery->execute($value) : null,
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
}