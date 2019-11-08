<?php

namespace app\modules\admin\models;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UserImport extends Model
{

    /**
     * @var UploadedFile
     */
    public $file;
    public $tempFilename;
    public $extension;

    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'xlsx, ods', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => Yii::t('user', 'File (xlsx or ods allowed)'),
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->tempFilename = uniqid() . '.' . $this->file->extension;
            $this->extension = $this->file->extension;
            $tempFilePath = Yii::getAlias('@runtime/temp/' . $this->tempFilename);
            $this->file->saveAs($tempFilePath);
            return true;
        } else {
            return false;
        }
    }

}