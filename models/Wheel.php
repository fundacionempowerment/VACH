<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;

/**
 * LoginForm is the model behind the login form.
 */
class Wheel extends ActiveRecord {

    public $dimensionAnswers = [0, 0, 0, 0, 0, 0, 0, 0];

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['coachee_id', 'date',], 'required'],
        ];
    }

    public function getCoachee() {
        return $this->hasOne(Coachee::className(), ['id' => 'coachee_id']);
    }

    public function getCoach() {
        return User::findOne(['id' => $this->coachee->coach_id]);
    }

    public function getAnswers() {
        return $this->hasMany(WheelAnswer::className(), ['wheel_id' => 'id']);
    }

    public function afterFind() {
        parent::afterFind();

        foreach ($this->answers as $answer) {
            $this->dimensionAnswers[(int) ($answer['answer_order'] / 10)] += $answer['answer_value'];
        }
        for ($i = 0; $i < count($this->dimensionAnswers); $i++) {
            $this->dimensionAnswers[$i] = 1 + ($this->dimensionAnswers[$i] / 10);
        }
    }

    public function customSave($answers) {
        if (count($answers) < 80) {
            $this->addError('answers', Yii::t('wheel', 'Some answers left.'));
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        if (!$this->validate()) {
            $transaction->rollBack();
            return false;
        }
        if (!$this->save()) {
            $transaction->rollBack();
            return false;
        }

        foreach ($answers as $answer) {
            if (!$answer->validate()) {
                $transaction->rollBack();
                return false;
            }
            $this->link('answers', $answer, ['wheel_id', 'id']);
        }

        if (!$this->save()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    public static function browse($coacheeId) {
        return (new Query())->select('id, date')
                        ->from('wheel')
                        ->where(['coachee_id' => $coacheeId])
                        ->orderBy('id desc')
                        ->all();
    }

}
