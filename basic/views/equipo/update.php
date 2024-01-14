<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Equipo $model */

$this->title = Yii::t('app', 'Update Equipo: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Equipos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="equipo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'listaCategorias' => $listaCategorias,
    ]) ?>

    <h2>Participantes</h2>
    <?= Html::a('Añadir Participante', ['add-participante', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    <?php if ($tieneParticipantes): ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'attribute' => 'usuario.nombre',
                    'label' => 'Nombre del Usuario',
                ],
                [
                    'attribute' => 'tipoParticipante.nombre',
                    'label' => 'Tipo de Participante',
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update} {expulsar}',
                    'buttons' => [
                        'expulsar' => function ($url, $model, $key) {
                            return Html::a('X', $url, [
                                'title' => Yii::t('app', 'Expulsar'),
                                'data-confirm' => Yii::t('app', '¿Estás seguro de que deseas expulsar a este participante?'),
                                'data-method' => 'post',
                                'class' => 'btn btn-primary',
                            ]);
                        },
                        // ... definiciones de otros botones ...
                    ],
                    'urlCreator' => function ($action, $model, $key, $index, $column) use ($equipo) {
                        if ($action === 'expulsar') {
                            return Url::toRoute(['expulsar-participante', 'equipoId' => $equipo->id, 'participanteId' => $model->id]);
                        } else {
                            return Url::toRoute(["participante/{$action}", 'id' => $model->id]);
                        }
                    },
                ]
            ],
        ]) ?>
        <?php else: ?>
            <p>Este equipo no tiene participantes.</p>
        <?php endif; ?>
</div>