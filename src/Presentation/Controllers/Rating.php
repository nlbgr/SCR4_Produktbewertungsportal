<?php

namespace Presentation\Controllers;

class Rating extends \Presentation\MVC\Controller {
    const PARAM_PRODUCT_ID = 'pid';
    const PARAM_COMMENT = 'comment';
    const PARAM_GRADE = 'grade';

    public function __construct(
        private \Application\SignedInUserQuery  $signedInUserQuery,
        private \Application\ProductQuery       $productQuery,
        private \Application\AddRatingCommand   $addRatingCommand
    ) {}

    public function GET_Create(): \Presentation\MVC\ActionResult {
        $pid = $this->getParam(self::PARAM_PRODUCT_ID);
        $p = $this->productQuery->execute($pid);
        return $this->view('ratingCreate', [
            'user' => $this->signedInUserQuery->execute(),
            'pid' => $pid,
            'pname' => $p->name,
            'comment' => $this->tryGetParam(self::PARAM_COMMENT, $value) ? $value : '',
            'context' => $this->getRequestUri()
        ]);
    }

    public function POST_Create(): \Presentation\MVC\ActionResult {
        $grade = $this->tryGetParam(self::PARAM_GRADE, $value) ? $value : '';
        $comment = $this->tryGetParam(self::PARAM_COMMENT, $value) ? $value : '';
        $pid = $this->tryGetParam(self::PARAM_PRODUCT_ID, $value) ? $value : '';
        $p = $this->productQuery->execute($pid);

        if (!isset($grade) || $grade <= 0 || $grade > 5) {
            return $this->view('ratingCreate', [
                'user' => $this->signedInUserQuery->execute(),
                'pid' => $pid,
                'pname' => $p->name,
                'comment' => $this->tryGetParam(self::PARAM_COMMENT, $value) ? $value : '',
                'context' => $this->getRequestUri(),
                'errors' => ["Please select a grade"]
            ]);
        }

        $result = $this->addRatingCommand->execute($grade, $comment, $pid);
        if ($result !== 0) {
            if ($result & \Application\AddProductCommand::Error_NotAuthenticated) {
                return $this->redirect('User', 'LogIn');
            }

            return $this->view('productCreate', [
                'user' => $this->signedInUserQuery->execute(),
                'pid' => $pid,
                'pname' => $p->name,
                'comment' => $this->tryGetParam(self::PARAM_COMMENT, $value) ? $value : '',
                'context' => $this->getRequestUri(),
                'errors' => ["Product creation failed"]
            ]);
        } else {
            return $this->redirect('Products', 'Index');
        }
    }
}