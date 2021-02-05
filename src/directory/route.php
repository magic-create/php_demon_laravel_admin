<?php

/*
|--------------------------------------------------------------------------
| Admin Custom Route
|--------------------------------------------------------------------------
|
| Use $router to define the route, and then use $run to run it
|
*/

//  Welcome
$router->any('/welcome', function() { return response('welcome'); });
