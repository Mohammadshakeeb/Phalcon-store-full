<?php

use Phalcon\Mvc\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class SignupController extends Controller
{

    public function IndexAction()
    {
        // return "Signup";
    }

    public function registerAction()
    {
        // echo "<pre>";
        // print_r($this->request->getpost());
        // echo "</pre>";
        // die;

        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "role" => $this->request->getPost('role')
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        // echo $jwt;
        // die;
        // return '<h1>registered</h1>';
        $user = new Users();
        $data = $this->request->getPost();
        $escaper = new \App\Component\Escape();
        $sanitizedArray = $escaper->sanitize($data);

        //assign value from the form to $user
        $user->assign(
            $sanitizedArray,
            [
                'name',
                'email',
                'password',
            ]
        );
        $user->role = $this->request->getPost('role');
        $user->token = $jwt;
        $success = $user->save();

        $this->view->success = $success;

        if ($success) {
            $this->view->message = "<h3>Thank you for signing up</h3>";
        } else {
            $this->view->message = "some errors occured while signing you up: <br>" . implode("<br>", $user->getMessages());
        }
    }
}
