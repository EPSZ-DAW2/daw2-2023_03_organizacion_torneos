<?php

use app\models\Direccion;
use app\models\Disciplina;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pista $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pista-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direccion_id')->dropDownList(Direccion::getListadoDirecciones(), ['prompt' => 'Seleccione una dirección' ])->label('Dirección'); ?>

    <?= $form->field($model, 'disciplina_id')->dropDownList(Disciplina::getListadoDisciplinas(), ['prompt' => 'Seleccione una disciplina' ])->label('Disciplina'); ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
