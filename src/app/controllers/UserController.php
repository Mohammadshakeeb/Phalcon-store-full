<?php

use Phalcon\Mvc\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
        $user = Users::find();
        $this->view->users = $user;
    }
    public function signupAction()
    {

        if ($this->request->isPost() && $this->request->getPost('email') && $this->request->getPost()['name']) {
            $user = new Users();
            $user->assign(
                $this->request->getPost(),
                [
                    'name',
                    'email'
                ]
            );

            $success = $user->save();
            if ($success) {
                unset($_POST);
                $_POST = array();
                $this->view->message = 'submitted!!!';
            } else {
                $this->view->message = $user->getMessages();
            }
        } else {
            $this->view->message = 'please fill form!!';
        }
    }
}
