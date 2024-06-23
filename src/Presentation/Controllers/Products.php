<?php
namespace Presentation\Controllers;

class Products extends \Presentation\MVC\Controller {
    const PARAM_PRODUCT_ID = 'pid';
    const PARAM_FILTER = 'f';
    const PARAM_PRODUCT_NAME = 'pname';
    const PARAM_MANUFACTURER_NAME = 'mname';

    public function __construct(
        private \Application\ProductsQuery          $productsQuery,
        private \Application\ProductSearchQuery     $productSearchQuery,
        private \Application\SignedInUserQuery      $signedInUserQuery,
        private \Application\RatingsChronoQuery     $ratingsChronoQuery,
        private \Application\ProductQuery           $productQuery,
        private \Application\AddProductCommand      $addProductCommand,
        private \Application\DeleteProductCommand   $deleteProductCommand,
    ) { }

    public function GET_Index(): \Presentation\MVC\ActionResult {
        return $this->view('productList', [
            'user' => $this->signedInUserQuery->execute(),
            'products' => $this->productsQuery->execute(),
            'context' => $this->getRequestUri()
        ]);
    }

    public function GET_Search(): \Presentation\MVC\ActionResult {
        $value = $this->tryGetParam(self::PARAM_FILTER, $v) ? $v : '';
        return $this->view('productSearch', [
            'user' => $this->signedInUserQuery->execute(),
            'filter' => $value,
            'products' => $this->productSearchQuery->execute($value),
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

    public function GET_Create(): \Presentation\MVC\ActionResult {
        return $this->view('productCreate', [
            'user' => $this->signedInUserQuery->execute(),
            'pname' => $this->tryGetParam(self::PARAM_PRODUCT_NAME, $value) ? $value : '',
            'mname' => $this->tryGetParam(self::PARAM_MANUFACTURER_NAME, $value) ? $value : '',
            'context' => $this->getRequestUri()
        ]);
    }

    public function POST_Create(): \Presentation\MVC\ActionResult {
        $pname = $this->getParam(self::PARAM_PRODUCT_NAME);
        $mname = $this->getParam(self::PARAM_MANUFACTURER_NAME);

        if ($pname === '' || $mname === '') {
            return $this->view('productCreate', [
                'user' => $this->signedInUserQuery->execute(),
                'pname' => $this->tryGetParam(self::PARAM_PRODUCT_NAME, $value) ? $value : '',
                'mname' => $this->tryGetParam(self::PARAM_MANUFACTURER_NAME, $value) ? $value : '',
                'context' => $this->getRequestUri(),
                'errors' => ["All input fields are required!"]
            ]);
        }

        $result = $this->addProductCommand->execute($pname, $mname);

        if ($result !== 0) {
            if ($result & \Application\AddProductCommand::Error_NotAuthenticated) {
                return $this->redirect('User', 'LogIn');
            }

            $errors = [];
            if ($result & \Application\AddProductCommand::Error_ProductAlreadyExists) {
                $errors[] = "Product already exists";
            }
            if (sizeof($errors) === 0) {
                $errors[] = "Product creation failed";
            }

            return $this->view('productCreate', [
                'user' => $this->signedInUserQuery->execute(),
                'pname' => $this->tryGetParam(self::PARAM_PRODUCT_NAME, $value) ? $value : '',
                'mname' => $this->tryGetParam(self::PARAM_MANUFACTURER_NAME, $value) ? $value : '',
                'context' => $this->getRequestUri(),
                'errors' => $errors
            ]);
        } else {
            return $this->redirect('Products', 'Index');
        }
    }

    public function POST_Delete(): \Presentation\MVC\ActionResult {
        $result = $this->deleteProductCommand->execute($this->getParam(self::PARAM_PRODUCT_ID));
        if ($result != 0) {
            if ($result & \Application\AddProductCommand::Error_NotAuthenticated) {
                return $this->redirect('User', 'LogIn');
            }

            // Just one error left that could have happened
            return $this->view('productCreate', [
                'user' => $this->signedInUserQuery->execute(),
                'pname' => $this->tryGetParam(self::PARAM_PRODUCT_NAME, $value) ? $value : '',
                'mname' => $this->tryGetParam(self::PARAM_MANUFACTURER_NAME, $value) ? $value : '',
                'context' => $this->getRequestUri(),
                'errors' => ["Product deletion failed"]
            ]);
        } else {
            return $this->redirect('Products', 'Index'); // Redirect to overview page, so that the post request cant be repeated
        }
    }
}