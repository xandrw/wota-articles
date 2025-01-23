<?php

use App\WebApi\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

//return function (array $context) {
//    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
//};

return fn(array $context) => new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
