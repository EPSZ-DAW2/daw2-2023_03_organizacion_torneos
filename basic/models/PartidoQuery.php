<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Partido]].
 *
 * @see Partido
 */
class PartidoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Partido[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Partido|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
