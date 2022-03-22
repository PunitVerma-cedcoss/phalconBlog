<?php

use Phalcon\Mvc\Controller;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

class AuthController extends Controller
{
    public function indexAction()
    {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files)->start();
        if ($this->request->ispost()) {
            $postData = $this->request->getPost();
            $users = new Users();
            $d = $users::findFirst(
                [
                    'conditions' => 'email = :email: AND password = :password:',
                    'bind' => [
                        'email' => $postData["email"],
                        'password' => $postData["password"],
                    ]
                ]
            );
            if ($d) {
                $session->set('user', $d->email);
                header("location:home");
            } else {
                $this->view->message =  "invalid username or password";
            }
        }
    }
    public function registerAction()
    {
        print_r($this->request->getPost());
        $users = new Users();
        $d = $users::findFirst(
            [
                'conditions' => 'email = :email:',
                'bind' => [
                    'email' => $this->request->getPost()["email"],
                ]
            ]
        );
        if ($d) {
            $this->view->message = "This email already exists";
        } else {
            $users->assign(
                $this->request->getPost(),
                [
                    'name',
                    'email',
                    'password'
                ]
            );
            if ($users->save()) {
                $this->view->message = "Successfully registered";
                header("location:/blogs");
            } else {
                $this->view->message = "Error registering";
            }
        }
        // die();
    }
}
