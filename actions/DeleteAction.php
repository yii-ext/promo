<?php
/**
 * Created by PhpStorm.
 * User: semenov
 * Date: 31.03.14
 * Time: 18:33
 */

namespace yii_ext\promo\actions;


use yii_ext\promo\models\PromoCodeModel;
use Yii;
/**
 * Class DeleteAction
 * @package yii-ext\promo\actions
 */
class DeleteAction extends \CAction
{
    /**
     * @param $id
     */
    public function run($id)
    {
        Yii::app()->db->createCommand()
            ->delete(PromoCodeModel::tableName(), 'id=:id', array('id' => $id));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('promoCode'));
    }

} 