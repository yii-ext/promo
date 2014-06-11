<?php
/**
 * Created by PhpStorm.
 * User: semenov
 * Date: 31.03.14
 * Time: 18:29
 */

namespace promo\controllers;


/**
 * Class PromoController
 * @package promo\controllers
 */
class PromoController extends \AdminController
{
    /**
     * @var string
     */
    public $layout = 'application.modules.admin.views.layouts.main';

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => array(
                'class' => '\promo\actions\IndexAction',
            ),
            'edit' => array(
                'class' => '\promo\actions\EditAction',
            ),
            'delete' => array(
                'class' => '\promo\actions\DeleteAction',
            ),
        );
    }

} 