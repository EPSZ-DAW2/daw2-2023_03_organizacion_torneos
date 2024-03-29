<?php

namespace app\models;


use yii\log\Logger;
use Yii;

/**
 * This is the model class for table "{{%log}}".
 *
 * @property int $id
 * @property string $level
 * @property string|null $category
 * @property string $log_time
 * @property string|null $prefix
 * @property string|null $message
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level'], 'required'],
            [['log_time'], 'safe'],
            [['prefix', 'message'], 'string'],
            [['level', 'category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level' => Yii::t('app', 'Level'),
            'category' => Yii::t('app', 'Category'),
            'log_time' => Yii::t('app', 'Log Time'),
            'prefix' => Yii::t('app', 'Prefix'),
            'message' => Yii::t('app', 'Message'),
            'fecha_ini' => Yii::t('app', 'Desde'),
            'fecha_fin' => Yii::t('app', 'Hasta'),
            'fecha_posterior' => Yii::t('app', 'Posteriores a'),
            'fecha_anterior' => Yii::t('app', 'Anteriores a'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return LogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LogQuery(get_called_class());
    }

    /*
     * Función que devuelve el listado de niveles de log presentes en la aplicación
     * En este caso se van a utilizar unicamente los 4 niveles predeterminados de yii
     * @return: array asociativo valor-nombre
     */
    public static function getListadoNiveles()
    {
        //En este caso solo se van a utilizar los niveles predeterminados de yii
        return [
            'error' => 'error',
            'warning' => 'warning',
            'info' => 'info',
            'trace' => 'trace',
        ];
    }
}
