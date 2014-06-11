<?php
/**
 * Created by PhpStorm.
 * User: semenov
 * Date: 31.03.14
 * Time: 18:31
 */

namespace yii_ext\promo\actions;

use yii_ext\promo\models\enums\PromoCodeType;
use yii_ext\promo\models;
use CAction;

/**
 * Class IndexAction
 * @package yii-ext\promo\actions
 */
class IndexAction extends CAction
{
    /**
     * @var string view file path
     */
    public $view = 'promo.views.promoCode';

    /**
     * Allow the user to enter a promo code to receive a specified price.
     *
     * @author Drozdenko Anna
     */
    public function run()
    {
        $model = new models\PromoCodeModel('search');
        if (isset($_POST[\CHtml::modelName($model)])) {
            $model->attributes = $_POST[\CHtml::modelName($model)];
            if (PromoCodeType::DISCOUNT_TYPE_PERCENTAGE == $model->discountType) {
                $model->scenario = 'percentage';
            }
            if ($model->save()) {
                $this->controller->refresh();
            }
        }
        $dataProvider = new \CActiveDataProvider('\promo\models\PromoCodeModel', array(
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        $this->controller->render($this->view, array(
            'model' => $model,
            'dataProvider' => $dataProvider
        ));
    }
} 