<?php

namespace app\modules\admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Company extends \app\models\Company
{

    public static function browse()
    {
        return Company::find()
            ->orderBy('name');
    }
}
