<?php

use Phalcon\Mvc\Controller;

class ProductController extends Controller
{
    public function indexAction()
    {
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
    }

    /**
     * form to add a new product
     */
    public function addAction()
    {
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // $this->view->post=$_POST;

    }

    /**
     * function to processs the new product data
     */
    public function addhelperAction()
    {

        $data = new Products();
        $values = $_POST;
        $value = $_POST;
        $eventmanager = $this->di->get('eventManager');
        $settings = Settings::find();
        $array = $eventmanager->fire('notifications:beforeSend', (object)$value, $settings);
        // echo "<pre>";
        // print_r($array);
        // echo "</pre>";
        // die();
        $val = array(
            'name' => $array->name,
            'description' => $array->description,
            'price' => $array->price,
            'tags' => $array->tags,
            'stock' => $array->stock
        );
        $data->assign(
            $val,
            [
                'name',
                'description',
                'price',
                'tags',
                'stock',
            ]
        );
        // $this->session->set('msg', "Product added successfully");
        $success = $data->save();

        $this->view->success = $success;

        if ($success) {
            $this->view->message = "<h3>Thank you for signing up</h3>";
        } else {
            $this->view->message = "some errors occured while signing you up: <br>" . implode("<br>", $data->getMessages());
        }
        // header('location:http://localhost:8080/product');
    }
}
