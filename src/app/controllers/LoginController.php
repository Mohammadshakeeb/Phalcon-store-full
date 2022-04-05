<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Cookies;

class LoginController extends Controller
{
    public function indexAction()
    {
        //return '<h1>Hello!!!</h1>';
    }

   
        /**
         * funtion to
         * process the login data 
         * add the user details and token in the session
         */
        public function loginAction()
        {
    
            // $data=$this->request->getpost();
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die();
          
    
    
    
               
                $data = $this->request->getpost();
                $escaper = new \App\Component\Escape();
                $sanitizedArray = $escaper->sanitizer($data);
                $email = $sanitizedArray['email'];
                $password = $sanitizedArray['password'];
               
                $data = Users::find(
    
                    [
                        'conditions' => 'email=:email: and password= :password:',
                        'bind' => [
                            'email' => $email,
                            'password' => $password
                        ]
    
                    ]
                );
    
                // echo "<pre>";
                // echo($data[0]->email);
                // echo "</pre>";
                //if any row matches the id and password
                if (count($data) > 0) {
    
                    $userdata = array(
                        'name' => $data[0]->name,
                        'role' => $data[0]->role,
                        'email' => $data[0]->email,
                        'password' => $data[0]->password,
                        'token' =>$data[0]->token
    
                    );
                    $this->session->login = $userdata;
               
                    global $container;
                  
                    if ($data[0]->role == 'Admin') {
                        header('location: http://localhost:8080/signup/index');
                    } elseif($data[0]->role == 'Manager') {
                        header('location: http://localhost:8080/product/index');
                    } elseif($data[0]->role == 'Accountant') {
                        header('location: http://localhost:8080/order/index');
                    }
                } else {
    
                    $this->session->set("msg", "Wrong credentials");
                    // $response = new Response();
                    // $response->setStatusCode(404, 'Not Found');
                    // $response->setContent("Sorry, Wrong credentials");
                    //  $response->redirect('user/error');
                    // $p = $response->getContent();
                    // $c = $response->getStatusCode();
                    // $a = $response->getReasonPhrase();
                    $adapter = new Stream('../app/logs/login.log');
                    $logger  = new Logger(
                        'messages',
                        [
                            'signup' => $adapter,
                        ]
                    );
                    $logger->alert("login failed");
    
    
                }
            }
        }
    


