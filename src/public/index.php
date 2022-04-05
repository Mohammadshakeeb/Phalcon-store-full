<?php

// print_r(apache_get_modules());
// echo "<pre>"; print_r($_SERVER); die;
// $_SERVER["REQUEST_URI"] = str_replace("/phalt/","/",$_SERVER["REQUEST_URI"]);
// $_GET["_url"] = "/";
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Session\Manager;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Cookies;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;
use App\component\locale;
use Phalcon\Config\ConfigFactory;

require("../vendor/autoload.php");

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$data=$loader->registerNamespaces(
    [
        'App\Component'=> APP_PATH. '/Component',
        'App\Listeners' => APP_PATH . '/listeners',
    ],
);

$loader->register();



$container = new FactoryDefault();

$eventManager = new EventsManager();

$eventManager->attach(
    'notifications',
    new App\Listeners\notificationListeners()
);
$eventManager->attach(
    'application:beforeHandleRequest',
    new App\Listeners\notificationListeners()
);


$application = new Application($container);

$application->setEventsManager($eventManager);
$container->set(
    'eventManager',
    $eventManager
);


$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);



$fileName = '../app/etc/config.php';
$factory  = new ConfigFactory();
$cookie=  $factory->newInstance('php', $fileName);
$container->set(
    'config',
    $cookie
);
// $container->set(
//     'db',
//     function () {
//         global $cookie;
//         return new Mysql(
//             [
//                 'host'     => $cookie->host,
//                 'username' => $cookie->root,
//                 'password' => $cookie->secret,
//                 'dbname'   => $cookie->dbname,
//             ]
//         );
//     }
// );

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'events',
            ]
        );
    }
);

$container->set(
    "cookies",
    function () {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    }
);

$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );

        $session
            ->setAdapter($files)
            ->start();

        return $session;
    }
);


try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
