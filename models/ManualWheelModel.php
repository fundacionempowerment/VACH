<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 *
 * @property integer $team_id
 * @property integer $wheel_type
 * @property integer $observer_id
 * @property integer $observed_id
 *
 */
class ManualWheelModel extends Model
{

    public $team_id;
    public $wheel_type;
    public $observer_id;
    public $observed_id;

    public function init()
    {
        $this->wheel_type = Wheel::TYPE_GROUP;

        return parent::init();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['team_id','wheel_type', 'observer_id', 'observed_id'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'team_id' => Yii::t('team', 'Team'),
            'wheel_type' => Yii::t('wheel', 'Wheel type'),
            'observer_id' => Yii::t('wheel', 'Observer'),
            'observed_id' => Yii::t('wheel', 'Observed'),
        ];
    }

}
