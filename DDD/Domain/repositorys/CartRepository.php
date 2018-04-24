<?php
namespace DDD\Domain\repositorys;

use DDD\Domain\user\User;
use SF\ORM\Artemis;

class CartRepository extends Artemis{

    public function __setOption()
    {
        $this->databses = 'eshop';
        $this->table = 'user';
    }

    public function findById($id){
        $userEntity =  $this->where(['_id'=>$id])->first([]);

        $user = new User();
        $user->id = $userEntity->id;
        $user->username = $userEntity->username;
        $user->status = $userEntity->status;

        return $user;
    }

    public function checkLogin($username,$passwd){
        $userEntity = $this->where(['username' => $username, 'passwd' => $passwd])->get();

        $user = new User();

        $user->username = $user->username;

        $user->nickname = $user->nickname;

        return $user;
    }

    public function findAll(){
        $userEntitys = $this->get([]);
        $userLst = [];
        foreach ($userEntitys as $userEntity){
            $user = new User();
            $user->id = $userEntity->id;
            $user->username = $userEntity->username;
            $user->status = $userEntity->status;

            array_push($userLst,$user);
        }
        return $userLst;
    }

    public function store(User $user){
        $this->update(['_id'=>$user->id],[
            'username' => $user->username,
            'passwd' => $user->passwd,
            'nickname' => $user->nickname
        ]);
    }
}