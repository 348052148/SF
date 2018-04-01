<?php
namespace SF\Route;

interface QueryController {


    public function findController($mapRef);

    public function findaAction($mapRef);

    public function getInstance($mapRef);

    public function findParames();
}