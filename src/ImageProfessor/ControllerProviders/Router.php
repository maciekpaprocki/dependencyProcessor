<?php

Namespace ImageProfessor\ControllerProviders;

use Silex\Application;
use Silex\ControllerProviderInterface;

class SilexProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return $app->redirect('/hello');
        });

        return $controllers;
    }
}
