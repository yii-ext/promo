<?php
/**
 * Created by PhpStorm.
 * User: semenov
 * Date: 31.03.14
 * Time: 17:40
 */

namespace promo\models\enums;


class PromoCodeType extends \CEnumerable
{
    const DISCOUNT_TYPE_PERCENTAGE = 1;
    const DISCOUNT_TYPE_DOLLAR_VALUE = 0;

    static $discountTypeArray = array(
        self::DISCOUNT_TYPE_PERCENTAGE => 'Percentage',
        self::DISCOUNT_TYPE_DOLLAR_VALUE => 'Dollar Value',
    );
} 