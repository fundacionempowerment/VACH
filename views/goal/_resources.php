<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
?>
<div class="new-goal">
    <table class="col-xs-12 hidden-xs">
        <tr>
            <td class="alert alert-success" WIDTH="50%">
                <?= Yii::t('goal', 'Things to conservate:') ?>
                <ul>
                    <?php foreach ($goal->resourcesToConserve as $resource): ?>
                        <li>
                            <?= $resource->description ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </td>
            <td class="alert alert-danger" WIDTH="50%">
                <?= Yii::t('goal', 'Things to eliminate:') ?>
                <ul>
                    <?php foreach ($goal->resourcesToEliminate as $resource): ?>
                        <li>
                            <?= $resource->description ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="alert alert-info" WIDTH="50%">
                <?= Yii::t('goal', 'Things to conquer:') ?>
                <ul>
                    <?php foreach ($goal->resourcesToConquer as $resource): ?>
                        <li>
                            <?= $resource->description ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </td>
            <td class="alert alert-warning" WIDTH="50%">
                <?= Yii::t('goal', 'Things to avoid:') ?>
                <ul>
                    <?php foreach ($goal->resourcesToAvoid as $resource): ?>
                        <li>
                            <?= $resource->description ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
    </table>
    <div class="col-md-6 alert alert-success hidden-sm hidden-md hidden-lg" > 
        <?= Yii::t('goal', 'Things to conservate:') ?>
        <ul>
            <?php foreach ($goal->resourcesToConserve as $resource): ?>
                <li>
                    <?= $resource->description ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-md-6 alert alert-info hidden-sm hidden-md hidden-lg"> 
        <?= Yii::t('goal', 'Things to conquer:') ?>
        <ul>
            <?php foreach ($goal->resourcesToConquer as $resource): ?>
                <li>
                    <?= $resource->description ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>        
    <div class="col-md-6 alert alert-danger hidden-sm hidden-md hidden-lg">
        <?= Yii::t('goal', 'Things to eliminate:') ?>
        <ul>
            <?php foreach ($goal->resourcesToEliminate as $resource): ?>
                <li>
                    <?= $resource->description ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>        
    <div class="col-md-6 alert alert-warning hidden-sm hidden-md hidden-lg">
        <?= Yii::t('goal', 'Things to avoid:') ?>
        <ul>
            <?php foreach ($goal->resourcesToAvoid as $resource): ?>
                <li>
                    <?= $resource->description ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <br />  
</div>