<?php

use app\models\Currency;

class CurrencyCest {
    public function _before(UnitTester $I) {
    }

    public function _after(UnitTester $I) {
    }

    public function fetchLastValueType(UnitTester $I) {
        $lastValue = Currency::fetchLastValue();

        $I->assertInstanceOf(Currency::className(), $lastValue);
    }

    public function fetchLastValueRate(UnitTester $I) {
        $lastValue = Currency::fetchLastValue();

        $I->assertGreaterThan(0, $lastValue->rate);
    }
}
