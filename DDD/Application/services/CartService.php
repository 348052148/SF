<?php
namespace DDD\Application\services;

use DDD\Domain\cart\Cart;
use DDD\Domain\repositorys\CartRepository;
use DDD\Domain\repositorys\GoodsRepository;
use UI\dtos\RequestDto;

class CartService {

    public function addGoodsToCart(RequestDto $goodsDto){
        $goodsRepository = new GoodsRepository();
        $goods = $goodsRepository->findById($goodsDto->sku);
        $cartRepository = new CartRepository();

        $cart = $cartRepository->findById($goodsDto->cartId);

        $cart->addGoods($goods,$goodsDto->num);

        $cartRepository->store($cart);
    }

    public function removeGoodsToCart(RequestDto $goodsDto){
        $cartRepository = new CartRepository();
        $cart = $cartRepository->findById($goodsDto->cartId);
        $cart->removeGoods($goodsDto->goodsId);

        $cartRepository->store($cart);
    }
}