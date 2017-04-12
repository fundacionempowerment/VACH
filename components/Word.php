<?php

namespace app\components;

use Yii;
use app\models\Person;
use app\models\Wheel;
use app\models\WheelQuestion;
use app\models\TeamMember;
use app\components\Downloader;
use app\components\Utils;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\SimpleType\Jc;

class Word
{

    private static $assessment;
    private static $phpWord;
    private static $section;
    private static $styles;

    const COMMON_FONT = 'common_font';
    const COMMON_PARAGRAPH = 'common_paragraph';

    static public function create($assessment)
    {
        self::$assessment = $assessment;

        self::$phpWord = new PhpWord();

        self::setupStyles();

        // New portrait section
        self::$section = self::$phpWord->addSection();

        $footer = self::$section->addFooter();
        $table = $footer->addTable();
        $table->addRow();
        $table->addCell(8000)->addImage(Yii::getAlias("@app/web/images/brands/footer-gray.png"), ['width' => 400]);
        $table->addCell(1000)->addPreserveText('Pag // {PAGE}', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

        self::addFrontPage();

        self::addTeamPage();
        self::addIndex();

        return self::$phpWord;
    }

    static private function setupStyles()
    {
        self::$phpWord->addFontStyle(self::COMMON_FONT, ['size' => 12, 'spaceAfter' => 240]);

        self::$phpWord->addParagraphStyle(self::COMMON_PARAGRAPH, array('alignment' => Jc::JUSTIFY, 'spaceAfter' => 20));

        self::$phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));
    }

    static private function addFrontPage()
    {
        self::$section->addImage(Yii::getAlias("@app/web/images/brands/01-frontpage.png"), ['width' => 600]);
    }

    static private function addTeamPage()
    {
        self::$section->addPageBreak();

        self::$section->addImage(Yii::getAlias("@app/web/images/brands/02-VACH.png"), ['width' => 600]);

        self::$section->addText(self::$assessment->team->company->name, null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        self::$section->addText(self::$assessment->team->name, null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        self::$section->addText(Yii::t('report', 'Report') . ' ' . Yii::$app->formatter->asDate('now'), null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        self::$section->addText(Yii::t('team', 'Company') . ': ' . self::$assessment->team->company->name);
        self::$section->addText(Yii::t('team', 'Team') . ': ' . self::$assessment->team->name);
        self::$section->addText(Yii::t('user', 'Coach') . ': ' . self::$assessment->team->coach->fullname);
        self::$section->addText(Yii::t('team', 'Sponsor') . ': ' . self::$assessment->team->sponsor->fullname);
        self::$section->addText(Yii::t('report', 'Natural Team') . ':');
        foreach (self::$assessment->team->members as $teamMember) {
            self::$section->addListItem($teamMember->member->fullname);
        }
    }

    static private function addIndex()
    {
        self::$section->addPageBreak();
        
        // Add text elements
        self::$section->addTitle(Yii::t('report', 'Index'), 1);
        $toc = self::$section->addTOC(['size' => 12]);
    }

}
