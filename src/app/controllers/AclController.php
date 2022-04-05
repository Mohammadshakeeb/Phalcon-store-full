<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;



class AclController extends Controller
{
    public function indexAction()
    {
    }
    /**
     * function to give permission and initialize acl file dynamically
     */
    public function buildAction()
    {
        //     $data = $this->request->getpost();
        //     echo "<pre>";
        //     print_r($data);
        //     echo "</pre>";

        //     $c = count($_POST);
        //     // echo $data['role'];
        //    $action= array_slice($_POST, 2, $c - 3);
        //     // print_r($action);
        //     // die;
        $data = Permissions::find();
        // print_r(json_decode($data[3]->action));
        // die;


        $aclfile = APP_PATH . '/security/acl.cache';
        if (true !== is_File($aclfile)) {

            $acl = new Memory();
            // $acl->addRole($data['role']);
            // $acl->addRole('Customer');
            // $acl->addRole('Guest');

            foreach ($data as $k => $v) {

                $acl->addRole($v->role);
                $acl->addComponent($v->controller, json_decode($v->action));
                $acl->allow($v->role, $v->controller, json_decode($v->action));
            }

            $acl->addComponent(
                'acl',
                [
                    'build',
                    'addrole',
                    'getrole',
                    'addmethod',
                    'getcomponent',
                    'addcomponent',
                    'getmethod',
                    'addmethod',
                    'permission'
                ]
            );
            // $acl->addComponent(
            //     $data['controller'],
            //     $action
            // );

            // $acl->allow($data['role'], $data['controller'], $action);
            $acl->allow('*', 'acl', [
                'build',
                'addrole',
                'getrole',
                'addmethod',
                'getcomponent',
                'addcomponent',
                'getmethod',
                'addmethod',
                'permission'
            ]);

            //  $acl->deny('Guest', 'order', '*');
            // $acl->allow('Guest', 'order', 'add');
            // $acl->allow('Admin');

            file_put_contents(
                $aclfile,
                serialize($acl)
            );
        } else {
            $acl = unserialize(
                file_get_contents($aclfile)
            );
        }
        echo "hii";
        die;
    }
    /**
     * function to add a new role
     */
    public function getroleAction()
    {

        echo   '<h1>FORM TO ADD NEW ROLES</h1>
        <br><br>
        <form method="POST" action="addrole">
    <label for="role"><b>ROLE<b></label>
    <input type="text" name="role" id="role"></form>
    <br><br>
    <input type="submit" name="submit" value="SUBMIT">';
    }
    /**
     * function to add a new role
     */
    public function addroleAction()
    {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        $data = new Roles();
        $data->role = $_POST['role'];
        $data->save();
        echo '<a href="http://localhost:8080/acl/getcomponent">ADD A NEW COMPONENT</a>';
    }
    /**
     * function to add a new controller
     */
    public function getcomponentAction()
    {

        echo   '<h1>FORM TO ADD NEW COMPONENTS</h1>
        <br><br>
        <form method="POST" action="addcomponent">
    <label for="role"><b>COMPONENT<b></label>
    <input type="text" name="controller" id="controller"></form>
    <br><br>
    <input type="submit" name="submit" value="SUBMIT">';
    }
    /**
     * function to add a new controller
     */
    public function addcomponentAction()
    {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        $data = new Components();
        $data->controller = $_POST['controller'];
        $data->save();
        echo '<a href="http://localhost:8080/acl/getmethod">ADD A NEW ACTION</a>';
    }

    public function getmethodAction()
    {
    }
    /**
     * function to add a new method/action
     */
    public function addmethodAction()
    {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        $data = new Actions();
        $data->method = $_POST['action'];
        $data->controller_id = $_POST['product'];
        $data->save();
        echo '<a href="http://localhost:8080/acl/permission">SET PERMISSION</a>';
    }


    public function permissionAction()
    {
    }
    /**
     * function to set permissions of various urls in the database
     */
    public function addpermissionAction()
    {
        $data = $this->request->getpost();
        echo "<pre>";
        print_r($data);
        echo "</pre>";

        $c = count($_POST);
        // echo $data['role'];
        $action = array_slice($_POST, 2, $c - 3);
        // print_r($action);
        // die;
        $actionn = array();
        foreach ($action as $k => $v) {
            array_push($actionn, $v);
        }
        $fill = new Permissions();
        $actionn = json_encode($actionn);
        $dat = array(
            'role' => $data['role'],
            'controller' => $data['controller'],
            'action' => $actionn
        );
        $fill->assign(
            $dat,
            [
                'role',
                'controller',
                'action'
            ]
        );
        // $fill->role=$data['role'];
        // $fill->controller=$data['controller'];
        // $fill->action=json_encode($action);
        $fill->save();
        die;

        header('location:http://localhost:8080/acl/build');
    }
}
