<?php

namespace app\components;

use Yii;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Fill;
use app\models\Person;
use app\models\Wheel;
use app\models\WheelQuestion;
use app\models\TeamMember;
use app\components\Downloader;
use app\components\Utils;
use yii\helpers\ArrayHelper;

class Presentation
{

    private static $team;
    private static $ppt;

    public static function create($team)
    {
        self::$team = $team;

        self::$ppt = new PhpPresentation();

        self::addFirstSlide();
        self::addGoldenRuleSlide();
        self::addFunctionalThinkingSlide();
        self::addCompetenceTableSlide();
        self::addPercetualAdjustmentSlide();
        self::addObservationProtocolSlide();

        self::addTeamTitleSlide();
        self::addTeamRelationsSlide();
        self::addTeamEffectivenessSlide();
        self::addTeamPerformanceSlide();
        self::addTeamCompentencesSlide();
        self::addTeamEmergentsSlide();

        self::addMembersTitleSlide();

        foreach ($team->members as $member) {
            if ($member->active) {
                self::addMemberTitleSlide($member);
                self::addMemberPerceptionSlide($member);
                self::addMemberCompentencesSlide($member);
                self::addMemberEmergentsSlide($member);
                self::addMemberRelationsSlide($member);
                self::addMemberPerformanceSlide($member);
            }
        }

        self::addThankYouSlide();

        return self::$ppt;
    }

    private static function addBackground($slide)
    {
        $slide->createRichTextShape()
                ->setHeight(768)
                ->setWidth(1024)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->setStartColor(new Color('FF000000'))
                ->setEndColor(new Color('FF000000'));
    }

    private static function addCPCLogo($slide)
    {
        $shape = $slide->createDrawingShape();

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath(Yii::getAlias("@app/web/images/logo_cpc.png"))
                ->setHeight(36)
                ->setOffsetX(800)
                ->setOffsetY(10);
    }

    private static function addFirstSlide()
    {
        $currentSlide = self::$ppt->getActiveSlide();

        self::addBackground($currentSlide);
        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(640)
                ->setOffsetX(20)
                ->setOffsetY(50);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun(self::$team->company->name);
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(40)
                ->setColor(new Color('FFFF0000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun(self::$team->name);
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(40)
                ->setColor(new Color('FFFFFFFF'));
    }

    private static function addGoldenRuleSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(800)
                ->setOffsetX(20)
                ->setOffsetY(50);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('reglas de oro');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(50)
                ->setColor(new Color('FFFF0000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('Ésta es la voz del equipo');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(35)
                ->setColor(new Color('FF000000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('Hipótesis sujeta a validación');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(35)
                ->setColor(new Color('FFFF0000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('No tomarlo personal');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(35)
                ->setColor(new Color('FF000000'));
    }

    private static function addFunctionalThinkingSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(10);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $textRun = $shape->createParagraph()->createTextRun('ajuste perceptual');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(30)
                ->setColor(new Color('FFFF0000'));

        $shape = $currentSlide->createDrawingShape();

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath(Yii::getAlias("@app/web/images/funcional_thinking.png"))
                ->setHeight(440)
                ->setOffsetX(40)
                ->setOffsetY(160);
    }

    private static function addCompetenceTableSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(10);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $textRun = $shape->createParagraph()->createTextRun('cuadro integrado de competencias');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(30)
                ->setColor(new Color('FFFF0000'));

        $shape = $currentSlide->createDrawingShape();

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath(Yii::getAlias("@app/web/images/integration_table.png"))
                ->setHeight(560)
                ->setOffsetX(30)
                ->setOffsetY(130);
    }

    private static function addPercetualAdjustmentSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(10);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $textRun = $shape->createParagraph()->createTextRun('pensamiento funcional y disfuncional');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(30)
                ->setColor(new Color('FFFF0000'));

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(240)
                ->setWidth(400)
                ->setOffsetX(30)
                ->setOffsetY(150);
        $shape->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('FFFF0000'));

        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('¿Quién creía que era?');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(26)
                ->setColor(new Color('FFFFFFFF'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('¿Quién creo que soy');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(26)
                ->setColor(new Color('FFFFFFFF'));

        $shape->createBreak();

        ///////////////
        $shape = $currentSlide->createRichTextShape()
                ->setHeight(240)
                ->setWidth(400)
                ->setOffsetX(30)
                ->setOffsetY(380);
        $shape->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('FF008000'));

        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('¿A qué me comprometo y cómo?');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(26)
                ->setColor(new Color('FFFFFFFF'));

        $shape->createBreak();

        ///////////////
        $shape = $currentSlide->createRichTextShape()
                ->setHeight(240)
                ->setWidth(400)
                ->setOffsetX(520)
                ->setOffsetY(150);
        $shape->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('FFFF0000'));

        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('¿Quién creía que eras?');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(26)
                ->setColor(new Color('FFFFFFFF'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('¿Quién he descubierto que eres?');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(26)
                ->setColor(new Color('FFFFFFFF'));

        $shape->createBreak();

        ///////////////
        $shape = $currentSlide->createRichTextShape()
                ->setHeight(240)
                ->setWidth(400)
                ->setOffsetX(520)
                ->setOffsetY(380);
        $shape->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('FF008000'));

        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('¿Cómo vamos a prevenir el conflicto?');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(26)
                ->setColor(new Color('FFFFFFFF'));

        $shape->createBreak();
    }

    private static function addObservationProtocolSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(800)
                ->setOffsetX(20)
                ->setOffsetY(50);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('protocolo de observación');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(50)
                ->setColor(new Color('FFFF0000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('1. Visión cromática');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(30)
                ->setColor(new Color('FF000000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('   (analogía con el semáforo: verde, amarillo y rojo)');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(26)
                ->setColor(new Color('FF000000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('2. El más crítico');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(30)
                ->setColor(new Color('FF000000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('3. El más benévolo');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(30)
                ->setColor(new Color('FF000000'));

        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('4. El menos o más autocrítico');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(30)
                ->setColor(new Color('FF000000'));
        $shape->createBreak();

        $textRun = $shape->createParagraph()->createTextRun('5. Cruces interrelacionales');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(30)
                ->setColor(new Color('FF000000'));
    }

    private static function addTeamTitleSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(700)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(10);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('Equipo');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(70)
                ->setColor(new Color('FFFF0000'));
    }

    private static function addTeamCompentencesSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        $teamId = self::$team->id;

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/competences',
                            'teamId' => $teamId,
                            'memberId' => 0,
                            'wheelType' => Wheel::TYPE_GROUP], true));

        $shape->setName("CPC")
                ->setDescription('CPC')
                ->setPath($path)
                ->setHeight(250)
                ->setOffsetX(70)
                ->setOffsetY(100);

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/competences',
                            'teamId' => $teamId,
                            'memberId' => 0,
                            'wheelType' => Wheel::TYPE_ORGANIZATIONAL], true));

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath($path)
                ->setHeight(250)
                ->setOffsetX(70)
                ->setOffsetY(370);

        self::addCPCLogo($currentSlide);
    }

    private static function addTeamRelationsSlide()
    {
        $members = self::$team->activeMemberList;

        $currentSlide = self::$ppt->createSlide();
        $groupRelationsMatrix = Wheel::getRelationsMatrix(self::$team->id, Wheel::TYPE_GROUP);
        self::addRelationsSlide($currentSlide, Yii::t('dashboard', 'Group Relations Matrix'), $members, $groupRelationsMatrix);

        $currentSlide = self::$ppt->createSlide();
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix(self::$team->id, Wheel::TYPE_ORGANIZATIONAL);
        self::addRelationsSlide($currentSlide, Yii::t('dashboard', 'Organizational Relations Matrix'), $members, $organizationalRelationsMatrix);
    }

    private static function addRelationsSlide($currentSlide, $title, $members, $data)
    {
        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(5);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $textRun = $shape->createParagraph()->createTextRun($title);
        $textRun->getFont()->setName('Coolvetica')
                ->setSize(20)
                ->setColor(new Color('FFFF0000'));

        $tableShape = $currentSlide->createTableShape(count($members) + 2);
        $tableShape->setWidth(920);
        $tableShape->setHeight(750);
        $tableShape->setOffsetX(10);
        $tableShape->setOffsetY(120);

        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('wheel', "Observer \\ Observed"))->getFont()->setSize(8);
        foreach ($members as $index => $name) {
            $cell = $row->nextCell();
            $cell->createTextRun($name)->getFont()->setSize(8);
        }
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('app', 'Avg.'))->getFont()->setSize(8);

        $observed_sum = [];
        foreach ($members as $observerId => $observer) {
            $row = $tableShape->createRow();
            if ($observerId > 0) {
                $observer_sum = 0;
                $observer_count = 0;

                $cell = $row->nextCell();
                $cell->createTextRun($members[$observerId])->getFont()->setSize(8);

                foreach ($members as $observedId => $observed) {
                    if ($observedId > 0) {
                        $cell = $row->nextCell();

                        foreach ($data as $datum) {
                            if ($datum['observer_id'] == $observerId && $datum['observed_id'] == $observedId) {
                                if ($datum['value'] > Yii::$app->params['good_consciousness']) {
                                    $class = 'FFDFF0D8';
                                } elseif ($datum['value'] < Yii::$app->params['minimal_consciousness']) {
                                    $class = 'FFF2DEDE';
                                } else {
                                    $class = 'FFFCF8E3';
                                }

                                $cell->createTextRun(round($datum['value'] * 100 / 4, 1) . '%')->getFont()->setSize(8);

                                $cell->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color($class));

                                $observer_sum += $datum['value'];
                                $observer_count++;
                                if (!isset($observed_sum[$observedId])) {
                                    $observed_sum[$observedId] = 0;
                                }
                                $observed_sum[$observedId] += $datum['value'];
                            }
                        }
                    }
                }
                if ($observer_count > 0) {
                    if ($observer_sum / $observer_count > Yii::$app->params['good_consciousness']) {
                        $class = 'FFDFF0D8';
                    } elseif ($observer_sum / $observer_count < Yii::$app->params['minimal_consciousness']) {
                        $class = 'FFF2DEDE';
                    } else {
                        $class = 'FFFCF8E3';
                    }

                    $cell = $row->nextCell();
                    $cell->createTextRun(round($observer_sum / $observer_count * 100 / 4, 1) . '%')->getFont()->setSize(8);
                    $cell->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color($class));
                }
            }
        }

        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('app', 'Avg.'))->getFont()->setSize(8);
        if ($observer_count > 0) {
            foreach ($observed_sum as $sum) {
                if ($sum / $observer_count > Yii::$app->params['good_consciousness']) {
                    $class = 'FFDFF0D8';
                } elseif ($sum / $observer_count < Yii::$app->params['minimal_consciousness']) {
                    $class = 'FFF2DEDE';
                } else {
                    $class = 'FFFCF8E3';
                }

                $cell = $row->nextCell();
                $cell->createTextRun(round($sum / $observer_count * 100 / 4, 1) . '%')->getFont()->setSize(8);
                $cell->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color($class));
            }
        }
    }

    private static function addTeamEffectivenessSlide()
    {
        $teamId = self::$team->id;

        $currentSlide = self::$ppt->createSlide();

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(5);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $textRun = $shape->createParagraph()->createTextRun(Yii::t('dashboard', 'Group Consciousness and Responsability Matrix'));
        $textRun->getFont()->setName('Coolvetica')
                ->setSize(20)
                ->setColor(new Color('FFFF0000'));

        $performanceMatrix = Wheel::getProdConsMatrix($teamId, Wheel::TYPE_GROUP);

        $rowsData = [];
        $members = self::$team->activeMemberList;
        foreach ($members as $index => $name) {
            foreach ($performanceMatrix as $row) {
                if ($index == $row['id']) {
                    $rowsData[] = $row;
                }
            }
        }

        // Prepare numbers
        $sumConsciousness = 0;
        $sumProductivity = 0;

        foreach ($rowsData as $rowData) {
            $sumConsciousness += abs($rowData['consciousness']);
            $sumProductivity += $rowData['productivity'];
        }

        $avgConsciousness = $sumConsciousness / count($performanceMatrix);
        $avgProductivity = $sumProductivity / count($performanceMatrix);

        $standar_deviation = Utils::standard_deviation(ArrayHelper::getColumn($performanceMatrix, 'consciousness'));
        $productivityDelta = Utils::variance(ArrayHelper::getColumn($performanceMatrix, 'productivity'));

        $tableShape = $currentSlide->createTableShape(count($rowsData) + 1);
        $tableShape->setWidth(920);
        $tableShape->setHeight(750);
        $tableShape->setOffsetX(10);
        $tableShape->setOffsetY(120);

        // Set first row
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('app', 'Description'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun($rowData['fullname'])->getFont()->setSize(8);
        }

        // How I see me
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'How I see me'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(round($rowData['steem'] * 4 / 100, 2))->getFont()->setSize(8);
        }

        // How they see me
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'How they see me'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(round($rowData['productivity'] * 4 / 100, 2))->getFont()->setSize(8);
        }

        // Monofactorial productivity
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Monofactorial productivity'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(round($rowData['productivity'], 1) . '%')->getFont()->setSize(8);
        }

        // Responsability
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Responsability'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(Utils::productivityText($rowData['productivity'], $avgProductivity, $productivityDelta, 2))->getFont()->setSize(8);

            $cell->getFill()->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color($rowData['productivity'] < $avgProductivity ? 'FFFCF8E3' : 'FFDFF0D8'));
        }

        // Avg
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Avg. mon. prod.'))->getFont()->setSize(8);
        $cell = $row->nextCell();
        $cell->createTextRun(round($avgProductivity, 1) . '%')->getFont()->setSize(8);
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Prod. deviation'))->getFont()->setSize(8);
        $cell = $row->nextCell();
        $cell->createTextRun((round($productivityDelta, 1)) . '%')->getFont()->setSize(8);

        // Consciousness
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Cons. gap'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(round(abs($rowData['consciousness']), 1) . '%')->getFont()->setSize(8);
        }

        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Consciousness'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(abs($rowData['consciousness']) > $avgConsciousness ? Yii::t('app', 'Low') : Yii::t('app', 'High'))->getFont()->setSize(8);
            $cell->getFill()->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color($rowData['consciousness'] > $avgConsciousness ? 'FFFCF8E3' : 'FFDFF0D8'));
        }

        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Avg. conc. gap'))->getFont()->setSize(8);
        $cell = $row->nextCell();
        $cell->createTextRun(round($avgConsciousness, 1) . '%')->getFont()->setSize(8);

        self::addCPCLogo($currentSlide);

        // --------- Organizational

        $performanceMatrix = Wheel::getProdConsMatrix($teamId, Wheel::TYPE_ORGANIZATIONAL);

        if (count($performanceMatrix) == 0) {
            return;
        }

        $currentSlide = self::$ppt->createSlide();

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(480)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(5);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $textRun = $shape->createParagraph()->createTextRun(Yii::t('dashboard', 'Organizational Consciousness and Responsability Matrix'));
        $textRun->getFont()->setName('Coolvetica')
                ->setSize(20)
                ->setColor(new Color('FFFF0000'));

        $rowsData = [];
        foreach ($members as $index => $name) {
            foreach ($performanceMatrix as $row) {
                if ($index == $row['id']) {
                    $rowsData[] = $row;
                }
            }
        }

        // Prepare numbers
        $sumConsciousness = 0;
        $sumProductivity = 0;

        foreach ($rowsData as $rowData) {
            $sumConsciousness += abs($rowData['consciousness']);
            $sumProductivity += $rowData['productivity'];
        }

        $avgConsciousness = $sumConsciousness / count($performanceMatrix);
        $avgProductivity = $sumProductivity / count($performanceMatrix);

        $standar_deviation = Utils::standard_deviation(ArrayHelper::getColumn($performanceMatrix, 'consciousness'));
        $productivityDelta = Utils::variance(ArrayHelper::getColumn($performanceMatrix, 'productivity'));

        $tableShape = $currentSlide->createTableShape(count($rowsData) + 1);
        $tableShape->setWidth(920);
        $tableShape->setHeight(750);
        $tableShape->setOffsetX(10);
        $tableShape->setOffsetY(120);

        // Set first row
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('app', 'Description'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun($rowData['fullname'])->getFont()->setSize(8);
        }

        // How I see me
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'How I see me'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(round($rowData['steem'] * 4 / 100, 2))->getFont()->setSize(8);
        }

        // How they see me
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'How they see me'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(round($rowData['productivity'] * 4 / 100, 2))->getFont()->setSize(8);
        }

        // Monofactorial productivity
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Monofactorial productivity'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(round($rowData['productivity'], 1) . '%')->getFont()->setSize(8);
        }

        // Responsability
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Responsability'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(Utils::productivityText($rowData['productivity'], $avgProductivity, $productivityDelta, 2))->getFont()->setSize(8);

            $cell->getFill()->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color($rowData['productivity'] < $avgProductivity ? 'FFFCF8E3' : 'FFDFF0D8'));
        }

        // Avg
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Avg. mon. prod.'))->getFont()->setSize(8);
        $cell = $row->nextCell();
        $cell->createTextRun(round($avgProductivity, 1) . '%')->getFont()->setSize(8);
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Prod. deviation'))->getFont()->setSize(8);
        $cell = $row->nextCell();
        $cell->createTextRun((round($productivityDelta, 1)) . '%')->getFont()->setSize(8);

        // Consciousness
        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Cons. gap'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(round(abs($rowData['consciousness']), 1) . '%')->getFont()->setSize(8);
        }

        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Consciousness'))->getFont()->setSize(8);
        foreach ($rowsData as $rowData) {
            $cell = $row->nextCell();
            $cell->createTextRun(abs($rowData['consciousness']) > $avgConsciousness ? Yii::t('app', 'Low') : Yii::t('app', 'High'))->getFont()->setSize(8);
            $cell->getFill()->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color($rowData['consciousness'] > $avgConsciousness ? 'FFFCF8E3' : 'FFDFF0D8'));
        }

        $row = $tableShape->createRow();
        $cell = $row->nextCell();
        $cell->createTextRun(Yii::t('dashboard', 'Avg. conc. gap'))->getFont()->setSize(8);
        $cell = $row->nextCell();
        $cell->createTextRun(round($avgConsciousness, 1) . '%')->getFont()->setSize(8);

        self::addCPCLogo($currentSlide);
    }

    private static function addTeamPerformanceSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        $teamId = self::$team->id;

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/performance',
                            'teamId' => $teamId,
                            'memberId' => 0,
                            'wheelType' => Wheel::TYPE_GROUP], true));

        $shape->setName("CPC")
                ->setDescription('CPC')
                ->setPath($path)
                ->setHeight(310)
                ->setOffsetX(70)
                ->setOffsetY(20);

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/performance',
                            'teamId' => $teamId,
                            'memberId' => 0,
                            'wheelType' => Wheel::TYPE_ORGANIZATIONAL], true));

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath($path)
                ->setHeight(310)
                ->setOffsetX(70)
                ->setOffsetY(370);

        self::addCPCLogo($currentSlide);
    }

    private static function addTeamEmergentsSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        $teamId = self::$team->id;

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/emergents',
                            'teamId' => $teamId,
                            'memberId' => 0,
                            'wheelType' => Wheel::TYPE_GROUP], true));

        $shape->setName("CPC")
                ->setDescription('CPC')
                ->setPath($path)
                ->setWidth(800)
                ->setOffsetX(70)
                ->setOffsetY(50);

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/emergents',
                            'teamId' => $teamId,
                            'memberId' => 0,
                            'wheelType' => Wheel::TYPE_ORGANIZATIONAL], true));

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath($path)
                ->setWidth(800)
                ->setOffsetX(70)
                ->setOffsetY(350);

        self::addCPCLogo($currentSlide);
    }

    private static function addMembersTitleSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(700)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(10);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('Miembros');
        $textRun->getFont()->setName('Coolvetica')
                ->setSize(70)
                ->setColor(new Color('FFFF0000'));
    }

    private static function addMemberTitleSlide($member)
    {
        $currentSlide = self::$ppt->createSlide();

        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(700)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(10);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun($member->member->fullname);
        $textRun->getFont()->setName('Coolvetica')
                ->setSize(60)
                ->setColor(new Color('FFFF0000'));
    }

    private static function addMemberPerceptionSlide($member)
    {
        $currentSlide = self::$ppt->createSlide();

        $teamId = self::$team->id;
        $memberId = $member->person_id;

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/perceptions',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_GROUP], true));

        $shape->setName("CPC")
                ->setDescription('CPC')
                ->setPath($path)
                ->setHeight(350)
                ->setOffsetX(70)
                ->setOffsetY(20);

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/perceptions',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_ORGANIZATIONAL], true));

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath($path)
                ->setHeight(350)
                ->setOffsetX(70)
                ->setOffsetY(370);

        self::addCPCLogo($currentSlide);
    }

    private static function addMemberCompentencesSlide($member)
    {
        $currentSlide = self::$ppt->createSlide();

        $teamId = self::$team->id;
        $memberId = $member->person_id;

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/competences',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_GROUP], true));

        $shape->setName("CPC")
                ->setDescription('CPC')
                ->setPath($path)
                ->setHeight(250)
                ->setOffsetX(70)
                ->setOffsetY(100);

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/competences',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_ORGANIZATIONAL], true));

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath($path)
                ->setHeight(250)
                ->setOffsetX(70)
                ->setOffsetY(370);

        self::addCPCLogo($currentSlide);
    }

    private static function addMemberPerformanceSlide($member)
    {
        $currentSlide = self::$ppt->createSlide();

        $teamId = self::$team->id;
        $memberId = $member->person_id;

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/performance',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_GROUP], true));

        $shape->setName("CPC")
                ->setDescription('CPC')
                ->setPath($path)
                ->setHeight(310)
                ->setOffsetX(70)
                ->setOffsetY(20);

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/performance',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_ORGANIZATIONAL], true));

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath($path)
                ->setHeight(310)
                ->setOffsetX(70)
                ->setOffsetY(370);

        self::addCPCLogo($currentSlide);
    }

    private static function addMemberRelationsSlide($member)
    {
        $currentSlide = self::$ppt->createSlide();

        $teamId = self::$team->id;
        $memberId = $member->person_id;

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/relations',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_GROUP], true));

        $shape->setName("CPC")
                ->setDescription('CPC')
                ->setPath($path)
                ->setHeight(370)
                ->setOffsetX(90)
                ->setOffsetY(20);

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/relations',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_ORGANIZATIONAL], true));

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath($path)
                ->setHeight(370)
                ->setOffsetX(90)
                ->setOffsetY(370);

        self::addCPCLogo($currentSlide);
    }

    private static function addMemberEmergentsSlide($member)
    {
        $currentSlide = self::$ppt->createSlide();

        $teamId = self::$team->id;
        $memberId = $member->person_id;

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/emergents',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_GROUP], true));

        $shape->setName("CPC")
                ->setDescription('CPC')
                ->setPath($path)
                ->setWidth(800)
                ->setOffsetX(70)
                ->setOffsetY(100);

        self::addCPCLogo($currentSlide);

        $currentSlide = self::$ppt->createSlide();

        $shape = $currentSlide->createDrawingShape();

        $path = Downloader::download(\yii\helpers\Url::toRoute([
                            '/graph/emergents',
                            'teamId' => $teamId,
                            'memberId' => $memberId,
                            'wheelType' => Wheel::TYPE_ORGANIZATIONAL], true));

        $shape->setName('CPC logo')
                ->setDescription('CPC logo')
                ->setPath($path)
                ->setWidth(800)
                ->setOffsetX(70)
                ->setOffsetY(100);

        self::addCPCLogo($currentSlide);
    }

    private static function addThankYouSlide()
    {
        $currentSlide = self::$ppt->createSlide();

        self::addBackground($currentSlide);
        self::addCPCLogo($currentSlide);

        $shape = $currentSlide->createRichTextShape()
                ->setHeight(700)
                ->setWidth(1000)
                ->setOffsetX(10)
                ->setOffsetY(10);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $textRun = $shape->createParagraph()->createTextRun('¡Muchas gracias!');
        $textRun->getFont()
                ->setName('Coolvetica')
                ->setSize(60)
                ->setColor(new Color('FFFF0000'));
    }

}
