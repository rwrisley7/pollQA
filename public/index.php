<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Session\Adapter\Files as Session;

//setup the application
try {
    //define app path constant to make things neater
    define("APP_PATH", "../");

    //load config
    $config = new ConfigIni(APP_PATH . "app/config/config.ini");

    //register autoloader
    $loader = new Loader();

    //tell loader where things are
    $loader->registerDirs(
        [
            APP_PATH . $config->application->controllersDir,
            APP_PATH . $config->application->modelsDir,
            APP_PATH . $config->application->formsDir,
        ]
    );

    $loader->register();

    //create dependency injector
    $di = new FactoryDefault();

    //setup view component
    $di->set(
        "view",
        function() use ($config) {
            $view = new View();
            
            $view->setViewsDir(APP_PATH . $config->application->viewsDir);

            return $view;
        }
    );

    //setup base URI so all generated URIs include the base project directory
    $di->set(
        "url",
        function() use ($config) {
            $url = new UrlProvider();
            $url->setBaseUri($config->application->baseUri);
            return $url;
        }
    );

    //setup database service
    $di->set(
        "db",
        function() use ($config) {
            return new DbAdapter(
                [
                    "host" => $config->database->host,
                    "username" => $config->database->username,
                    "password" => $config->database->password,
                    "dbname" => $config->database->name,
                ]
            );
        }
    );

    //setup and start session (for user persistence and storing flash messages)
    $di->setShared(
        "session",
        function() {
            $session = new Session();

            $session->start();

            return $session;
        }
    );

    //setup actual application component
    $application = new Application($di);

    //handle request
    $response = $application->handle();
    $response->send();
} catch(\Exception $e) {
    echo "Exception: ", $e->getMessage();
}
