<?php

namespace ImageProfessor\ControllerProviders;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Filesystem\Filesystem;

class SilexProvider implements ControllerProviderInterface
{
    public function __construct($faculty)
    {
        $this->faculty = $faculty;
    }
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
        $names = [];
        foreach ($this->faculty->professors as $professor) {
            $names[] = $professor->name;
        }

        $fs = new FileSystem();
        $fs->remove($this->faculty->baseCacheDestination);
        $names = '('.implode('|', $names).')';
        $faculty = $this->faculty;
        $controllers->get('/{processor}/{path}', function (Application $app, $processor, $path) use ($faculty) {
            $exten = [];
            preg_match($faculty->extenstions, $path, $exten);
            $exten = ltrim($exten[0], '.');
            if (empty($exten)) {
                return $app->abort(404);
            }
            $faculty->process($processor, $path);
            $imagePath = $faculty->getLastProcessed()[0];

            return $app->sendFile($imagePath, 200, array('Content-Type' => 'image/'.$exten));

        })//->assert('processor','/'.$names.'/');
       ->assert('path', $this->faculty->extenstions);

        return $controllers;
    }
}
