<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%disciplina}}".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 *
 * @property Pista[] $pista
 * @property Torneo[] $torneos
 */
class Disciplina extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%disciplina}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 1000],
            [['nombre'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Nombre'),
            'descripcion' => Yii::t('app', 'Descripcion'),
        ];
    }

    /**
     * Gets query for [[Pista]].
     *
     * @return \yii\db\ActiveQuery|PistaQuery
     */
    public function getPista()
    {
        return $this->hasMany(Pista::class, ['disciplina_id' => 'id']);
    }

    /**
     * Gets query for [[Torneos]].
     *
     * @return \yii\db\ActiveQuery|TorneoQuery
     */
    public function getTorneos()
    {
        return $this->hasMany(Torneo::class, ['disciplina_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return DisciplinaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisciplinaQuery(get_called_class());
    }

    public static function getListadoDisciplinasPorId()
    {
        return ArrayHelper::map(Disciplina::find()->all(), 'id', 'nombre');
    }

    public static function getListadoDisciplinasPorNombre()
    {
        return ArrayHelper::map(Disciplina::find()->all(), 'nombre', 'nombre');
    }

}
