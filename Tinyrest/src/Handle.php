<?php

namespace Tinyrest;

use \Tinyrest\Exceptions\InvalidPathException;
use \Slim\Slim;
use \JsonApiView;
use \JsonApiMiddleware;

class Handle
{
    private $app;
    private $routers = [];

    public function __construct(array $paths, $data_dir = './')
    {
        $slim = $this->slim_boot();
        if($data_dir[strlen($data_dir) - 1] !== '/') {
            $data_dir = $data_dir . '/';
        }
        foreach ($paths as $path) {
            $path = preg_replace("/[^0-9a-z]/", "", trim(strtolower($path)));
            if(!$path) {
                throw new InvalidPathException;
            }
            $this->routers[$path] = new Router($path, $data_dir, $slim);
        }
        $this->slim_run();
    }

    public function router($path)
    {
        $path = preg_replace("/[^0-9a-z]/", "", trim(strtolower($path)));
        if(!$path || !isset($this->routers[$path])) {
            throw new InvalidPathException;
        }
        return $this->routers[$path];
    }

    private function slim_boot()
    {
        $app = $this->app = new Slim();
        $app->view(new JsonApiView());
        $app->add(new JsonApiMiddleware());
        return $app;
    }

    private function slim_run()
    {
        $this->app->config('debug', false);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->run();
    }
}