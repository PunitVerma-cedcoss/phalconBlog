<?php

use Phalcon\Mvc\Controller;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

class EditController extends Controller
{

    public function IndexAction()
    {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files)->start();
        // if session is not set 🖖
        if ($session->get("user") == "") {
            header("location:/auth");
        }
        $d = $this->request->getQuery('_url');
        $id = explode("/", $d);
        if (isset($id[2])) {
            if (is_numeric($id[2])) {
                print_r($id[2]);
                $blog = new Blogs();
                $data = $blog::findFirst([
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $id[2],
                    ]
                ]);
                if ($data) {
                    $this->view->data = $data;
                } else {
                    $this->view->data = "no data found";
                }
            }
        }

        if ($this->request->ispost()) {
            print_r($this->request->getpost());
            $blogs = new Blogs();
            $x = $blog::findFirst([
                'conditions' => 'id = :id:',
                'bind' => [
                    'id' => $id[2],
                ]
            ]);
            $x->blog_image = $this->request->getpost()["image"];
            $x->blog_title = $this->request->getpost()["title"];
            $x->blog_content = $this->request->getpost()["content"];
            $x->blog_tags = $this->request->getpost()["tags"];
            if ($x->save()) {
                echo "data updated";
                header("location:/blogs/" . $id[2]);
            } else {
                echo "some errorr occured";
            }
            die();
        }

        // if ($this->request->isget()) {
        //     $this->view->data = $this->request->getQuery();
        // }
    }
}
