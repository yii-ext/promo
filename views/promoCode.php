<?php
$this->breadcrumbs = array(
    'Promo Codes Management'
);
?>
<div class="row">
    <div class="col-md-6">
        <h2>Add a New Code</h2>
        <?php $this->renderPartial('promo.views._promoCodeForm', array('model' => $model)); ?>
    </div>
    <div class="col-md-6">
        <h2>Active Codes</h2>
        <?php
        $this->widget('booster.widgets.TbGridView', array(
            'id' => 'promo-cod-list',
            'dataProvider' => $dataProvider,
            'ajaxUpdate' => true,
            'enableHistory' => true,
            'filter' => $model,
            'template' => '{items}{pager}',
            'columns' => array(
                array(
                    'name' => 'title',
                    'header' => 'Promo Name',
                    'type' => 'raw',
                    'filter' => false,
                ),
                array(
                    'name' => 'discountValue',
                    'header' => 'Value',
                    'filter' => false,
                    'value' => '(\promo\models\enums\PromoCodeType::DISCOUNT_TYPE_PERCENTAGE == $data->discountType) ? $data->discountValue . "% off" : "$" . $data->discountValue'
                ),
                array(
                    'name' => 'endDate',
                    'header' => 'Expiration Date',
                    'type' => 'raw',
                    'value' => 'StringHelper::setClassToText(DateHelper::getFrenchFormatDbDate($data->endDate), "urgent")',
                    'filter' => false,
                ),
                array(
                    'header' => 'Manage',
                    'class' => 'booster.widgets.TbButtonColumn',
                    'template' => '{update}{delete}',
                    'buttons' => array(
                        'update' => array(
                            'url' => "CHtml::normalizeUrl(array('edit', 'id'=>\$data->id))",
                            'options' => array(
                                'success' => 'js:function(data){$.fn.yiiListView.update("message",{});}'
                            ),
                        ),
                        'delete' => array(
                            'url' => "CHtml::normalizeUrl(array('delete', 'id'=>\$data->id))",
                            'options' => array(
                                'success' => 'js:function(data){$.fn.yiiListView.update("message",{});}'
                            ),
                        ),
                    ),
                ),
            ),
        ));
        ?>
    </div>
</div>
