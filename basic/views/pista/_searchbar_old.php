<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Disciplina;

?>

<?php
$form = ActiveForm::begin([
    'method' => 'get',
    'action' => ['pistas'], 
]); 


$icon = '<div class="position-relative">{input}<i class="fas fa-search form-icon"></i></div>';

$abierto = (!empty($model->direccionProvincia) || !empty($model->direccionCiudad) || !empty($model->direccionPais) || !empty($model->disciplinaNombre))
?>

<div class="search-bar shadow p-3 mb-5 bg-body rounded w-75 mx-auto">
    <div class="busqueda-global row px-3">
        <div class="col mx-1 px-0"><?= $form->field($model, 'busquedaGlobal', ['template' => $icon])->textInput(['placeholder' => 'Introduzca un término de búsqueda']) ?></div>
        <div class="col-auto mx-1 px-0"><?= Html::a('Eliminar filtros', ['pistas'], ['class' => 'btn btn-outline-secondary fw-bold shadow-sm',]) ?></div>
        <div class="col-auto mx-1 px-0"><?= Html::submitButton(Yii::t('app', 'Buscar'), ['class' => 'btn btn-success fw-bold shadow-sm']) ?></div>
    </div>
    <div class="form-group mx-2 mt-2 mb-0">
        <details <?= $abierto ? 'open' : '' ?>>
        <summary class="text-center btn btn-success fw-bold shadow-sm">Filtros avanzados</summary>
        <fieldset id="filtros">
            <div class="busqueda-filtros">
                <div class="row filtros-pistas mt-4">
                    <?= $form->field($model, 'direccionProvincia', ['template' => '{input}'])->textInput(['placeholder' => 'Introduzca una provincia']) ?>
                    <?= $form->field($model, 'direccionCiudad', ['template' => '{input}'])->textInput(['placeholder' => 'Introduzca una ciudad']) ?>
                    <?= $form->field($model, 'direccionPais', ['template' => '{input}'])->textInput(['placeholder' => 'Introduzca un país']) ?>
                    <?= $form->field($model, 'disciplinaNombre', ['template' => '{input}'])->dropDownList(Disciplina::getListadoDisciplinasPorNombre(), ['prompt' => 'Seleccione una disciplina' ]); ?>
                </div>
            </div>
        </fieldset>
        </details>
          
    </div>
</div>



<?php ActiveForm::end(); ?>