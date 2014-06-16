<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'verticalForm',
    'action' => '/admin/promo/index',
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'role' => 'form',
    )));
?>
<?php if ('editPromoCode' == Yii::app()->controller->action->id): ?>
    <?php echo CHtml::hiddenField('returnUrl', $returnUrl); ?>
<?php endif; ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'title', array('label' => 'Promo Name')); ?>
        <?php echo $form->textField($model, 'title', array('class' => 'form-control', 'placeholder' => 'Name this promotion')); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'code', array('label' => 'Code')); ?>
        <?php echo $form->textField($model, 'code', array('class' => 'form-control', 'placeholder' => 'What code is used to activate this promotion')); ?>
        <?php echo $form->error($model, 'code'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'discountType', array('label' => 'Discount Type')); ?>
        <?php echo $form->dropDownList($model, 'discountType', yii_ext\promo\models\enums\PromoCodeType::$discountTypeArray, array('class' => 'form-control')); ?>
        <?php echo $form->error($model, 'discountType'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'discountValue', array('label' => 'Value')); ?>
        <?php echo $form->textField($model, 'discountValue', array('class' => 'form-control', 'placeholder' => 'How much is this code worth')); ?>
        <?php echo $form->error($model, 'discountValue'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, "startDate", array('label' => 'Start Date')); ?>
        <?php
        $this->widget('booster.widgets.TbDateTimePicker', array(
            'model' => $model,
            'attribute' => 'startDate',
            'options' => array(
                'dateFormat' => 'yy-mm-dd',
                'showAnim' => 'fold',
                'changeMonth' => true,
                'minDate' => 0,
            ),
            'htmlOptions' => array(
                'class' => 'form-control',
                'placeholder' => 'What date/time should the promotion start'
            ),
        ));
        ?>
        <?php echo $form->error($model, "startDate"); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, "endDate", array('label' => 'End Date')); ?>
        <?php
        $this->widget('booster.widgets.TbDateTimePicker', array(
            'model' => $model,
            'attribute' => 'endDate',
            'options' => array(
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => true,
            ),
            'htmlOptions' => array(
                'class' => 'form-control',
                'placeholder' => 'What date/time should the promotion end'
            ),
        ));
        ?>
        <?php echo $form->error($model, "endDate"); ?>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Which Packages Does This Discount Apply To?</label>
        <?php echo $form->checkBoxGroup($model, 'applyTo1Month', array(), array('label' => '1 Month ')); ?>
        <?php echo $form->checkBoxGroup($model, 'applyTo3Month', array(), array('label' => '3 Month ')); ?>
        <?php echo $form->checkBoxGroup($model, 'applyTo6Month', array(), array('label' => '6 Month')); ?>
        <?php echo $form->error($model, "apply"); ?>
    </div>
<?php
$this->widget('booster.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => ('editPromoCode' == Yii::app()->controller->action->id) ? 'Save' : 'Activate',
));
?>
<?php if ('editPromoCode' == Yii::app()->controller->action->id): ?>
    <?php echo CHtml::link('Go Back', $returnUrl, array('class' => 'btn btn-default')); ?>
<?php endif; ?>
<?php $this->endWidget(); ?>