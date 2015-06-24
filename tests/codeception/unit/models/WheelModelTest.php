<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use app\models\WheelAnswer;

class WheelModelTest extends TestCase {

    protected function setUp() {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['user']);
    }

    public function testGetAnswers() {
        $answerTypes = [
            WheelAnswer::ANSWER_NUMBERS_0_TO_4,
            WheelAnswer::ANSWER_WORST_TO_OPTIMAL,
            WheelAnswer::ANSWER_NEVER_TO_ALWAYS,
            WheelAnswer::ANSWER_NONE_TO_ALL,
            WheelAnswer::ANSWER_NOTHING_TO_ABSOLUTLY,
            WheelAnswer::ANSWER_OPTIMAL_TO_WORST,
            WheelAnswer::ANSWER_ALWAYS_TO_NEVER,
            WheelAnswer::ANSWER_ALL_TO_NONE,
            WheelAnswer::ANSWER_ABSOLUTLY_TO_NOTHING,
        ];

        foreach ($answerTypes as $answerType) {
            $items = WheelAnswer::getAnswerLabels($answerType);
            $this->assertTrue(is_array($items), 'Not an array in wheel type ' . $answerType);
            $this->assertCount(5, $items, 'Not enough items in wheel type ' . $answerType);
        }
    }

}
