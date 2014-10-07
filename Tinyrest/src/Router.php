<?php

namespace Tinyrest;

use \Tinyrest\Exceptions\InvalidEventException;
use \Tinyrest\Exceptions\DirectoryNotWriteableException;
use \Tinyrest\Exceptions\DataFileNotReadable;
use \Tinyrest\Exceptions\CorruptDataFile;
use \Tinyrest\Exceptions\CallbackNotFoundException;
use \Closure;

class Router
{
    private $app;
    private $path;
    private $file_name;
    private $lock_file;
    private $data = [];
    private $listeners = [
        'create' => [],
        'update' => [],
        'delete' => []
    ];

    private function listen()
    {
        $instance = $this;
        $this->on('create', function() use ($instance) {
            $instance->write();
        });
        $this->on('update', function() use ($instance) {
            $instance->write();
        });
        $this->on('delete', function() use ($instance) {
            $instance->write();
        });
    }

    private function fire()
    {
        $args = func_get_args();
        $action = array_shift($args);
        foreach ($this->listeners[$action] as $callback) {
            call_user_func_array($callback, $args);
        }
    }


    private function input()
    {
        $data = json_decode($this->app->request->getBody());
        if(json_last_error() !== JSON_ERROR_NONE) {
            $data = $this->app->request->params();
        }
        return (object) $data;
    }

    private function write()
    {
        $locked = true;
        while (file_exists($this->lock_file)) {
            clearstatcache();
            usleep(10000);
        }
        touch($this->lock_file);
        file_put_contents($this->file_name, json_encode(array_values($this->data)));
        unlink($this->lock_file);
    }

    private function routes()
    {
        $instance = $this;

        $this->app->get( '/' . $this->path, function() use ($instance) {
            return $instance->app->render(200, [
                'response' => $instance->data
            ]);
        });

        $this->app->get( '/' . $this->path . '/:id', function($id) use ($instance) {
            for ($i = 0; $i < count($instance->data); $i++) { 
                if($instance->data[$i]->id === $id)
                    break;
            }
            if(!isset($instance->data[$i]))
                return $instance->app->render(404, ['error' => true]);
            return $instance->app->render(200, [
                'response' => $instance->data[$i]
            ]);
        });

        $this->app->post( '/' . $this->path, function() use ($instance) {
            $data = $instance->input();
            if(isset($data->id))
                unset($data->id);
            $data->id = uniqid(null);
            array_push($instance->data, $data);
            $this->fire('create', $data);
            return $instance->app->render(201, [
                'response' => $data
            ]);
        });

        $this->app->put( '/' . $this->path . '/:id', function($id) use ($instance) {
            $new_data = $instance->input();
            for ($i = 0; $i < count($instance->data); $i++) { 
                if($instance->data[$i]->id === $id)
                    break;
            }
            if(!isset($instance->data[$i]))
                return $instance->app->render(404, ['error' => true]);
            $old_data =  $instance->data[$i];
            $new_data->id = $id;
            $instance->data[$i] = $new_data;
            $this->fire('update', $new_data, $old_data);
            return $instance->app->render(200, [
                'response' => $new_data
            ]);
        });

        $this->app->delete( '/' . $this->path . '/:id', function($id) use ($instance) {
            for ($i = 0; $i < count($instance->data); $i++) { 
                if($instance->data[$i]->id === $id)
                    break;
            }
            if(!isset($instance->data[$i]))
                return $instance->app->render(404, ['error' => true]);
            $old_data = $instance->data[$i];
            unset($instance->data[$i]);
            $this->fire('delete', $old_data);
            return $instance->app->render(200, [
                'response' => $old_data
            ]);
        });
    }

    private function load_data()
    {
        if(!is_writable(getcwd())) {
            throw new DirectoryNotWriteableException;
        }

        if(!file_exists($this->file_name)) {
            file_put_contents($this->file_name, '[]');
        }

        if(!is_readable($this->file_name)) {
            throw new DataFileNotReadable;
        }

        $data = json_decode(file_get_contents($this->file_name), FALSE);
        if(json_last_error() !== JSON_ERROR_NONE) {
            throw new CorruptDataFile;    
        }
        $this->data = $data;
    }

    /**
     * Public Methods Start
     */

    /**
     * [on description]
     * @param  String $event     The event to listen to [create, update, delete]
     * @param  Closure $callback The callback to fire once event has happened
     * @return Int               The callback's index inside the listener array
     */
    public function on($event, Closure $callback) 
    {
        if(!isset($this->listeners[$event])) {
            throw new InvalidEventException;
        }
        array_push($this->listeners[$event], $callback);
        return max(array_keys($this->listeners[$event]));
    }

    /**
     * [off description]
     * @param  Int $index   The callback's index inside the listener array
     */
    public function off($index) 
    {
        if(!isset($this->listeners[$event])) {
            throw new InvalidEventException;
        }
        if(!isset($this->listeners[$event][$index])) {
            throw new CallbackNotFoundException;   
        }
    }

    public function __construct($path, $data_dir, $app)
    {
        $this->app = $app;
        $this->path = $path;
        $this->file_name = $data_dir . $path  . '.json';
        $this->lock_file = $data_dir . $path . '.lock';
        $this->load_data();
        $this->routes();
        $this->listen();
    }
}