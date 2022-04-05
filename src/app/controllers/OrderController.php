<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function indexAction()
    {
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
    }

    public function addAction()
    {

        $this->view->products = Products::find();
    }

    public function addhelperAction()
    {


        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // die();

        $data = new Orders();
        $values = $_POST;
        $eventmanager = $this->di->get('eventManager');
        $settings = Settings::find();
        $array = $eventmanager->fire('notifications:afterSend', (object)$values, $settings);
        // echo "<pre>";
        // print_r($array);
        // echo "</pre>";
        // die();
        $val = array(
            'customer_name' => $array->customer_name,
            'customer_address' => $array->customer_address,
            'product' => $array->product,
            'zipcode' => $array->zipcode,
            'quantity' => $array->quantity
        );
        // echo "<pre>";
        // print_r($values);
        // echo "</pre>";
        // die();
        $data->assign(
            $val,
            [
                'customer_name',
                'customer_address',
                'product',
                'zipcode',
                'quantity',
            ]
        );
        // $data->zipcode=1212;
        // $this->session->set('msg',"Product added successfully");
        $success = $data->save();

        $this->view->success = $success;

        if ($success) {
            $this->view->message = "<h3>Thank you for signing up</h3>";
        } else {
            $this->view->message = "some errors occured while signing you up: <br>" . implode("<br>", $data->getMessages());
        }
    }
}
