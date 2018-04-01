<?php
namespace SF\Core;

class ServiceApplication extends BaseApplication {

    protected $controller_suffix = 'Service';

    public function doAction()
    {
        $service = new \Yar_Server($this->getControllerInstance());
        $service->handle();
    }
}