<?php

use Phalcon\Mvc\Controller;

class SettingController extends Controller
{
    public function indexAction()
    {
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
    }


    public function settingAction()
    {
    }

    public function settingshelperAction()
    {

        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        // echo $_POST['default_price'];
        $id = 1;
        $data = Settings::find(

            [
                'conditions' => 'id=:id:',
                'bind' => [
                    'id' => $id,
                ]

            ]
        );
        // echo $data[0]->default_zipcode;
        // die();
        if ($data) {
            $data[0]->title_op = $_POST['title_op'];
            $data[0]->default_price = $_POST['default_price'];
            $data[0]->default_stock = $_POST['default_stock'];
            $data[0]->default_zipcode = $_POST['default_zipcode'];
            $data[0]->save();
            header('location:http://localhost:8080/setting');
        }
    }
}
