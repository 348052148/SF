<?php
namespace SDF\Core;


use SDF\Http\Request;
use SDF\Http\Response;

class WebApplication extends BaseApplication {

    /**
     * 这里定义获取数据规则
     * @throws \Exception
     */
    public function doAction()
    {
        Dispatcher::setController($this->controller_instance);

        Dispatcher::setAction($this->getAction());

        Dispatcher::setParames(array_merge([
            new Request(),
            new Response()
        ],$this->getParames()));

        Dispatcher::run();
    }

}