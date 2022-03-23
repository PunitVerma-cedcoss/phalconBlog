<?php

use Phalcon\Mvc\Controller;
use Phalcon\Di;
use Phalcon\Mvc\Model\Manager as ModelsManager;

use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

class BlogsController extends Controller
{
    public function indexAction()
    {
        $di = new Di();

        $di->set(
            "modelsManager",
            function () {
                return new ModelsManager();
            }
        );
        $blogs = new Blogs();
        $blogs = Blogs::query()->columns('*')->innerjoin('Users', 'users.email = Blogs.user_email', 'users')->execute();
        $this->view->data = $blogs;

        // return '<h1>Hello World!</h1>';
    }
    public function createAction()
    {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files)->start();
        $session->setAdapter($files)->start();
        if (!($session->get("user"))) {
            header("location:/auth");
        }
        if ($this->request->ispost()) {
            $blogs = new Blogs();
            $blogs->assign(
                [
                    "user_email" => $session->get("user"),
                    "blog_title" => $this->request->getpost()["title"],
                    "blog_content" => $this->request->getpost()["content"],
                    "blog_image" => $this->request->getpost()["image"],
                    "blog_tags" => $this->request->getpost()["tags"],
                ]
            );
            if ($blogs->save()) {
                header("location:/blogs");
            }
        }
    }
    public function blogAction()
    {
        if ($this->request->getQuery()) {
            $bid = explode("/", $this->request->getQuery()["_url"]);
            if (isset($bid[3])) {
                $blogs = new Blogs();
                $data = $blogs::findFirst($bid[3]);
                if ($data) {
                    $this->view->data = $data;
                    // seding some blogs data 😎
                    $this->view->data2 = $blogs::find(["limit" => 3]);
                } else {
                    echo "id is invalid redicrect";
                }
            } else {
                header("location:/blogs");
            }
        }
    }
    public function myblogsAction()
    {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files)->start();
        if (!($session->get("user"))) {
            header("location:/auth");
        }
        $blogs = new Blogs();
        $data = $blogs::find(
            [
                'conditions' => 'user_email = :email:',
                'bind' => [
                    'email' => $session->get("user"),
                ]
            ]
        );
        if ($data) {
            $this->view->data = $data;
        } else {
            header("location:/blogs");
        }
    }
}
