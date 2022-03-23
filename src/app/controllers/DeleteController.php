<?php

use Phalcon\Mvc\Controller;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

class DeleteController extends Controller
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
                $res = $data->delete();
                if ($res) {
                    if ($session->get("user") != $data->user_email) {
                        header("location:/blogs");
                    }
                    $this->view->data = $data;
                } else {
                    header("location:/blogs");
                }
            }
        }
        header("location:/blogs");
        // if ($this->request->isget()) {
        //     $this->view->data = $this->request->getQuery();
        // }
    }
}
