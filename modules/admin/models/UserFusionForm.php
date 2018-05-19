<?php

namespace app\modules\admin\models;

use app\controllers\SiteController;
use app\models\User;
use Yii;
use yii\base\Model;

class UserFusionForm extends Model
{
    public $originUserId;
    public $destinationUserId;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['originUserId', 'destinationUserId'], 'required'],
            [['originUserId', 'destinationUserId'], 'required'],
            ['destinationUserId', 'compare', 'compareAttribute' => 'originUserId', 'operator' => '!='],
        ];
    }

    public function attributeLabels()
    {
        return [
            'originUserId' => Yii::t('user', 'Origin User'),
            'destinationUserId' => Yii::t('user', 'Destination User'),
        ];
    }


    public function getOriginUser()
    {
        $originUser = User::findOne($this->originUserId);
        return $originUser;
    }

    public function getDestinationUser()
    {
        $destinationUser = User::findOne($this->destinationUserId);
        return $destinationUser;
    }

    public function fuse()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->update('person', ['coach_id' => $this->destinationUserId], ['coach_id' => $this->originUserId])->execute();
            Yii::$app->db->createCommand()->update('company', ['coach_id' => $this->destinationUserId], ['coach_id' => $this->originUserId])->execute();
            Yii::$app->db->createCommand()->update('team', ['coach_id' => $this->destinationUserId], ['coach_id' => $this->originUserId])->execute();
            Yii::$app->db->createCommand()->update('team_coach', ['coach_id' => $this->destinationUserId], ['coach_id' => $this->originUserId])->execute();
            Yii::$app->db->createCommand()->update('stock', ['coach_id' => $this->destinationUserId], ['coach_id' => $this->originUserId])->execute();
            Yii::$app->db->createCommand()->update('payment', ['coach_id' => $this->destinationUserId], ['coach_id' => $this->originUserId])->execute();
            Yii::$app->db->createCommand()->update('user_session', ['user_id' => $this->destinationUserId], ['user_id' => $this->originUserId])->execute();
            Yii::$app->db->createCommand()->update('log', ['coach_id' => $this->destinationUserId], ['coach_id' => $this->originUserId])->execute();
            Yii::$app->db->createCommand()->update('feedback', ['user_id' => $this->destinationUserId], ['user_id' => $this->originUserId])->execute();

            Yii::$app->db->createCommand()->delete('user', ['id' => $this->originUserId])->execute();

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            SiteController::addFlash('error', $e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }
}