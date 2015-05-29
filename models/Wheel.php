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

    public $answers;
    public $dimensionAnswers;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['userId', 'date', 'answers'], 'required'],
            [['date', 'coachName', 'coacheeName', 'coachId', 'coacheeId'], 'safe'],
        ];
    }

    public function getCoachee() {
        return $this->hasOne(Client::className(), ['id' => 'coachee_id']);
    }

    public function getCoach() {
        return User::findOne(['id' => $this->coachee->coach_id]);
    }

    public function afterFind() {
        parent::afterFind();

        $this->answers = (new Query())->select('answer_value')
                ->from('wheel_answer')
                ->where(['wheel_id' => $this->id])
                ->orderBy('answer_order')
                ->column();

        $sum = 0;
        $this->dimensionAnswers = [];
        if (count($this->answers) == 80)
            for ($i = 0; $i < 80; $i++) {
                $sum += $this->answers[$i];
                if (($i + 1) % 10 == 0) {
                    $this->dimensionAnswers[] = $sum / 10 + 1;
                    $sum = 0;
                }
            }
    }

    public function customSave() {
        $command = yii::$app->db->createCommand();

        $command->insert('wheel', [
            'coachee_id' => $this->coachee->id,
            'date' => $this->date,
        ])->execute();

        $this->id = yii::$app->db->getLastInsertID();

        for ($i = 0; $i < 80; $i++) {
            $command = yii::$app->db->createCommand();

            $command->insert('wheel_answer', [
                'wheel_id' => $this->id,
                'answer_order' => $i,
                'answer_value' => $this->answers[$i],
            ])->execute();
        }
    }

    public function validate($attributeNames = null, $clearErrors = true) {
        if (count($this->answers) < 80)
            $this->addError('answers', Yii::t('wheel', 'Some answers left.'));

        return !$this->hasErrors();
    }

    public static function browse($coacheeId) {
        return (new Query())->select('id, date')
                        ->from('wheel')
                        ->where(['coachee_id' => $coacheeId])
                        ->orderBy('id desc')
                        ->all();
    }

}
