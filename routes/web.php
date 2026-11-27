<?php

use App\Controllers\HomeController;
use Slim\App;

return function (App $app, HomeController $controller): void {
    $app->get('/', [$controller, 'home']);
    $app->get('/privacidade', [$controller, 'privacy']);
    $app->post('/contato', [$controller, 'contact']);
};
