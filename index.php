<?php

class Dispatcher
{
    public function dispatch($method, $uri)
    {
        // Redirect all requests to /public/index.php
        $this->redirect('/public/index.php');
    }

    private function redirect($location)
    {
        header("Location: {$location}");
        exit;
    }
}

// Tworzymy instancję klasy Dispatcher i wywołujemy metodę dispatch
$dispatcher = new Dispatcher();
$dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);