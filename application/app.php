<?php

require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor/autoload.php';

$container = new \League\Container\Container();

$logger = new \SimpleLog\Logger(__DIR__.DIRECTORY_SEPARATOR.'test.txt', 'test');

$container->add(\Psr\Log\LoggerInterface::class, $logger);

\Rrmode\Platform\Foundation\Application::initialize($container);

$app = \Rrmode\Platform\Foundation\Application::make();
