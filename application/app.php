<?php

require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor/autoload.php';

$container = new \League\Container\Container();

$configuration = new class ($container) extends \Rrmode\Platform\Bootstrap\AbstractContainerConfiguration {
    public function __construct(
        private \League\Container\Container $container){
        $this->runInitializers($this->container);
    }

    public function add(string $abstract, Closure $concrete)
    {
        return $this->container->add($abstract, $concrete);
    }

    public function addSingleton(string $abstract, Closure $concrete)
    {
        return $this->container->addShared($abstract, $concrete);
    }

    protected function getLoggerImplementation(): ?\Psr\Log\LoggerInterface
    {
        return new \SimpleLog\Logger(__DIR__.DIRECTORY_SEPARATOR.'test.txt', 'test');
    }

    protected function getDispatcherImplementation(): ?\Psr\EventDispatcher\EventDispatcherInterface
    {
        return new \League\Event\EventDispatcher();
    }

    protected function getContainerImplementation(): \Psr\Container\ContainerInterface
    {
        return $this->container;
    }
};

\Rrmode\Platform\Foundation\Application::initialize($container);

$app = \Rrmode\Platform\Foundation\Application::make();
