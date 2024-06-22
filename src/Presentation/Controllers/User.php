<?php

namespace Presentation\Controllers;

class User extends \Presentation\MVC\Controller {
    const PARAM_USER_NAME = 'un';
    const PARAM_PASSWORD = 'password';
    const PARAM_REP_PASSWORD = 'repPassword';

    public function __construct(
        private \Application\SignInCommand $signInCommand,
        private \Application\SignOutCommand $signOutCommand,
        private \Application\SignedInUserQuery $signedInUserQuery,
        private \Application\CheckIfUserExistsCommand $checkIfUserExistsCommand,
        private \Application\SignUpCommand $signUpCommand
    ) {}

    public function GET_LogIn(): \Presentation\MVC\ActionResult {
        $u = $this->signedInUserQuery->execute();
        if ($u !== null) {
            return $this->redirect('Home', 'Index');
        }

        return $this->view('login', [
            'user' => $u,
            'userName' => $this->tryGetParam(self::PARAM_USER_NAME, $value) ? $value : ''
        ]);
    }

    public function POST_LogIn(): \Presentation\MVC\ActionResult {
        if (!$this->signInCommand->execute($this->getParam(self::PARAM_USER_NAME), $this->getParam(self::PARAM_PASSWORD))) {
            return $this->view('login', [
                'user' => $this->signedInUserQuery->execute(),
                'userName' => $this->getParam(self::PARAM_USER_NAME),
                'errors' => ['Invalid username or password']
            ]);
        } else {
            return $this->redirect('Home', 'Index');
        }
    }

    public function POST_LogOut(): \Presentation\MVC\ActionResult {
        $this->signOutCommand->execute();
        return $this->redirect('Home', 'Index');
    }

    public function GET_SignUp(): \Presentation\MVC\ActionResult {
        $u = $this->signedInUserQuery->execute();
        if ($u !== null) {
            return $this->redirect('Home', 'Index');
        }

        return $this->view('signup', [
            'user' => $this->signedInUserQuery->execute(),
            'userName' => $this->tryGetParam(self::PARAM_USER_NAME, $value) ? $value : ''
        ]);
    }

    public function POST_SignUp(): \Presentation\MVC\ActionResult {
        if ($this->getParam(self::PARAM_PASSWORD) !== $this->getParam(self::PARAM_REP_PASSWORD)) {
            return $this->view('signup', [
                'user' => $this->signedInUserQuery->execute(),
                'userName' => $this->getParam(self::PARAM_USER_NAME),
                'errors' => ['Passwords do not match']
            ]);
        } else if ($this->checkIfUserExistsCommand->execute($this->getParam(self::PARAM_USER_NAME))) {
            return $this->view('signup', [
                'user' => $this->signedInUserQuery->execute(),
                'userName' => $this->getParam(self::PARAM_USER_NAME),
                'errors' => ['Username already exists']
            ]);
        } else if ($this->getParam(self::PARAM_PASSWORD) === '' ) {
            return $this->view('signup', [
                'user' => $this->signedInUserQuery->execute(),
                'userName' => $this->getParam(self::PARAM_USER_NAME),
                'errors' => ['A password must be set']
            ]);
        } else {
            if (!$this->signUpCommand->execute($this->getParam(self::PARAM_USER_NAME), $this->getParam(self::PARAM_PASSWORD))) {
                return $this->view('signup', [
                    'user' => $this->signedInUserQuery->execute(),
                    'userName' => $this->getParam(self::PARAM_USER_NAME),
                    'errors' => ['Unknown Error. Please try again later']
                ]);
            } else {
                return $this->redirect('Home', 'Index');
            }
        }
    }
}