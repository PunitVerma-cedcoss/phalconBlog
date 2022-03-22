<?php

use Phalcon\Mvc\Controller;
use Phalcon\Di;
use Phalcon\Mvc\Model\Manager as ModelsManager;


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
}
