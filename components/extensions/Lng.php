<?php

namespace app\components\extensions;

use Yii;

class Lng
{
    public $words = [
        'ru' => [
            'clothes-for-boy'  => 'Одежда для мальчиков',
            'clothes-for-girl' => 'Одежда для девочек',
            'clothes-for-baby' => 'Одежда для малышей',
            'sleepwear'        => 'Пижамы',
            'dress'            => 'Платья',
            'recommend'        => 'Рекомендованные товары',
            'bay'              => 'Купить',
            'title'            => 'Название',
            'description'      => 'Описание',
            'price'            => 'Цена',
            'home'             => 'Главная',
            'for-boy'          => 'Для мальчиков',
            'for-girl'         => 'Для девочек',
            'payment-delivery' => 'Оплата и доставка',
            'contacts'         => 'Контакты',
            'unisex'           => 'Унисекс',
        ],
        'uk' => [
            'clothes-for-boy'  => 'Одяг для хлопчиків',
            'clothes-for-girl' => 'Одяг для дівчаток',
            'clothes-for-baby' => 'Одяг для немовлят',
            'sleepwear'        => 'Піжами',
            'dress'            => 'Плаття',
            'recommend'        => 'Рекомендовані товари',
            'bay'              => 'Купити',
            'title'            => 'Назва',
            'description'      => 'Опис',
            'price'            => 'Ціна',
            'home'             => 'Головна',
            'for-boy'          => 'Для хлопчиків',
            'for-girl'         => 'Для дівчаток',
            'payment-delivery' => 'Оплата та доставка',
            'contacts'         => 'Контакти',
            'unisex'           => 'Унісекс',
        ]
    ];

    public function getLng()
    {
        return Yii::$app->request->get('lng') == 'uk' ? 'uk' : 'ru';
    }

    public function getDictionary()
    {
        return $this->words[$this->getLng()];
    }
 }