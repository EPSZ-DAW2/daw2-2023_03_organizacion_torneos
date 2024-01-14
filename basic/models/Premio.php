<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%premio}}".
 *
 * @property int $id
 * @property string $nombre trofeo
 * @property string|null $descripcion
 * @property int $categoria_id
 * @property int $torneo_id
 * @property int|null $equipo_id
 *
 * @property Categorium $categoria
 * @property Equipo $equipo
 * @property Torneo $torneo
 */
class Premio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%premio}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'categoria_id', 'torneo_id'], 'required'],
            [['categoria_id', 'torneo_id', 'equipo_id'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 500],
            [['nombre'], 'unique'],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorium::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['torneo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Torneo::class, 'targetAttribute' => ['torneo_id' => 'id']],
            [['equipo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipo::class, 'targetAttribute' => ['equipo_id' => 'id']],
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
            'categoria_id' => Yii::t('app', 'Categoria ID'),
            'torneo_id' => Yii::t('app', 'Torneo ID'),
            'equipo_id' => Yii::t('app', 'Equipo ID'),
        ];
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery|CategoriumQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categorium::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Equipo]].
     *
     * @return \yii\db\ActiveQuery|EquipoQuery
     */
    public function getEquipo()
    {
        return $this->hasOne(Equipo::class, ['id' => 'equipo_id']);
    }

    /**
     * Gets query for [[Torneo]].
     *
     * @return \yii\db\ActiveQuery|TorneoQuery
     */
    public function getTorneo()
    {
        return $this->hasOne(Torneo::class, ['id' => 'torneo_id']);
    }

    /**
     * {@inheritdoc}
     * @return PremioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PremioQuery(get_called_class());
    }
}
