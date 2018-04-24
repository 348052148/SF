<?php
namespace DDD\Application\services;

use DDD\Domain\repositorys\UserRepository;
use UI\dtos\RequestDto;

class UserService {

    public function login(RequestDto $loginDto){

        $userRepository = new UserRepository();
        try {
            $user = $userRepository->checkLogin($loginDto->username,$loginDto->passwd);
            $user->login();
        }catch (\Exception $e){

        }
        $userRepository->store($user);
        //这里暂不处理返回值情况
        return $user;
    }

    public function register(RequestDto $registerDto){
        $userService = new \DDD\Domain\user\UserService();
        $user = $userService->register($registerDto->username,$registerDto->passwd);
    }

    public function logout(RequestDto $loginDto){
        $userRepository = new UserRepository();
        $user = $userRepository->findById($loginDto->id);
        $user->logout();

        $userRepository->store($user);
    }
}