<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * LoginForm is the model behind the login form.
 */
class WheelModel extends Model {

    public $id;
    public $coacheeId;
    public $coachId;
    public $date;
    public $answers;
    public $dimensionAnswers;
    public $coachName;
    public $coacheeName;

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

    public function browse() {
        return (new Query())->select('id, date')
                        ->from('wheel')
                        ->where(['user_id' => $this->coacheeId])
                        ->orderBy('id desc')
                        ->all();
    }

    public function save() {
        $command = yii::$app->db->createCommand();

        $command->insert('wheel', [
            'user_id' => $this->coacheeId,
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

    public function populate() {
        $this->attributes = (new Query())->select('id, user_id as coacheeId, date')
                ->from('wheel')
                ->where(['id' => $this->id])
                ->one();

        $this->answers = (new Query())->select('answer_value')
                ->from('wheel_answer')
                ->where(['wheel_id' => $this->id])
                ->orderBy('answer_order')
                ->column();

        $this->attributes = (new Query())->select('name as coacheeName, surname as coacheeSurname, coach_id as coachId')
                ->from('user')
                ->where(['id' => $this->coacheeId])
                ->one();

        $this->attributes = (new Query())->select('name as coachName')
                ->from('user')
                ->where(['id' => $this->coachId])
                ->one();

        $sum = 0;
        if (count($this->answers) == 80)
            for ($i = 0; $i < 80; $i++) {
                $sum += $this->answers[$i];
                if (($i + 1) % 10 == 0) {
                    $this->dimensionAnswers[] = $sum / 10 + 1;
                    $sum = 0;
                }
            }
    }

    public function populateLast() {
        $this->id = (new Query())->select('Id')
                ->from('wheel')
                ->where(['user_id' => $this->coacheeId])
                ->orderBy('id desc')
                ->scalar();

        $this->populate();
    }

    public function validate($attributeNames = null, $clearErrors = true) {
        if (count($this->answers) < 80)
            $this->addError('answers', Yii::t('wheel', 'Some answers left.'));

        return !$this->hasErrors();
    }

}
