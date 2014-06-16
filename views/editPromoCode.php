<?php
/**
 * @author    Drozdenko Anna
 * @link      http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2013 Zfort Group
 * @license   http://www.zfort.com/terms-of-use
 */
$this->breadcrumbs = array(
    'Promo Codes Management' => "/{$this->module->id}/promo/index",
    'Edit Promo Code ' . $model->title
)
?>

<div class="row">
    <div class="col-md-6">
        <h2>Edit Promo Code <?php echo $model->title; ?></h2>
        <?php $this->renderPartial('yii_ext.promo.views._promoCodeForm', array('model' => $model, 'returnUrl' => $returnUrl)); ?>
    </div>
</div>