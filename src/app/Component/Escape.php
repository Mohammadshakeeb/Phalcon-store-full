<?php

namespace App\Component;

use Phalcon\Escaper;

class Escape
{
    /**
     * function to sanitize signup data using escaper
     */


    public function sanitize($data)
    {


        $escaper = new Escaper();
        //    print_r($escaper);
        //    die();
        $arr = array(
            'name' => $escaper->escapeHtml($data['name']),
            'email' => $escaper->escapeHtml($data['email']),
            'password' => $escaper->escapeHtml($data['password'])
        );
        //   $logger->alert("This is an alert message");
        return $arr;
    }
    /**
     * function to sanitize login data using escaper
     */
    public function sanitizer($data)
    {

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();
        $escaper = new Escaper();
        //    print_r($escaper);
        //    die();
        $arr = array(
            'email' => $escaper->escapeHtml($data['email']),
            'password' => $escaper->escapeHtml($data['password'])
        );
        //   $logger->alert("This is an alert message");
        return $arr;
    }
}
