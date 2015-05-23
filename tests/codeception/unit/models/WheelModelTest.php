<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use app\models\WheelModel;

class WheelModelTest extends TestCase {

    protected function setUp() {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['user']);
    }

    public function testGetAnswers() {
        $answerTypes = [
            WheelModel::WORST_TO_OPTIMAL,
            WheelModel::NEVER_TO_ALWAYS,
            WheelModel::NONE_TO_ALL,
            WheelModel::OPTIMAL_TO_WORST,
            WheelModel::ALWAYS_TO_NEVER,
            WheelModel::ALL_TO_NONE,
            WheelModel::NUMBERS_0_TO_4,
        ];

        foreach ($answerTypes as $answerType) {
            $items = WheelModel::getAnswers($answerType);
            $this->assertTrue(is_array($items), 'Not an array in wheel type ' . $answerType);
            $this->assertCount(5, $items, 'Not enough items in wheel type ' . $answerType);
        }
    }

}
