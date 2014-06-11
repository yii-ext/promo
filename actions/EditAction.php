<?php
/**
 * Created by PhpStorm.
 * User: semenov
 * Date: 31.03.14
 * Time: 18:32
 */

namespace promo\actions;

use promo\models\PromoCodeModel;
use Yii;

/**
 * Class EditAction
 * @package promo\actions
 */
class EditAction extends \CAction
{
    /**
     * @var string view file path
     */
    public $view = 'promo.views.editPromoCode';

    /**
     * Editing promo code.
     *
     * @param integer $id Id of the promo code
     *
     * @author Drozdenko Anna
     */
    public function run($id)
    {
        $model = PromoCodeModel::model()->findByPk($id);
        $returnUrl = Yii::app()->request->urlReferrer;
        if (isset($_POST[\CHtml::modelName($model)])) {
            $model->attributes = $_POST[\CHtml::modelName($model)];
            $returnUrl = Yii::app()->request->getPost('returnUrl');
            if ($model->save()) {
                $this->controller->refresh();
            }
        }
        $this->controller->render($this->view, array(
            'model' => $model,
            'returnUrl' => $returnUrl,
        ));
    }
}