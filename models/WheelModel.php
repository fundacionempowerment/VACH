<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * LoginForm is the model behind the login form.
 */
class WheelModel extends Model {

    const WORST_TO_OPTIMAL = 0;
    const NEVER_TO_ALWAYS = 1;
    const NONE_TO_ALL = 2;
    const OPTIMAL_TO_WORST = 100;
    const ALWAYS_TO_NEVER = 101;
    const ALL_TO_NONE = 102;

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

    public static function getAnswers($setName) {
        switch ($setName) {
            case WheelModel::WORST_TO_OPTIMAL: return [
                    Yii::t('wheel', 'worst'),
                    Yii::t('wheel', 'bad'),
                    Yii::t('wheel', 'fair'),
                    Yii::t('wheel', 'good'),
                    Yii::t('wheel', 'optimal')];
            case WheelModel::NONE_TO_ALL: return [
                    Yii::t('wheel', 'none'),
                    Yii::t('wheel', 'few'),
                    Yii::t('wheel', 'some'),
                    Yii::t('wheel', 'many'),
                    Yii::t('wheel', 'all')];
            case WheelModel::NEVER_TO_ALWAYS: return [
                    Yii::t('wheel', 'never'),
                    Yii::t('wheel', 'sometimes'),
                    Yii::t('wheel', 'often'),
                    Yii::t('wheel', 'usually'),
                    Yii::t('wheel', 'always')];
            case WheelModel::OPTIMAL_TO_WORST: return array_reverse(WheelModel::getAnswers(WheelModel::WORST_TO_OPTIMAL));
            case WheelModel::ALL_TO_NONE: return array_reverse(WheelModel::getAnswers(WheelModel::NONE_TO_ALL));
            case WheelModel::ALWAYS_TO_NEVER: return array_reverse(WheelModel::getAnswers(WheelModel::NEVER_TO_ALWAYS));
        }
    }

}
