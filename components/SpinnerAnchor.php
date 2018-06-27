<?php

namespace app\components;


use rmrevin\yii\fontawesome\FontAwesome;
use yii\base\Widget;
use yii\helpers\Html;

class SpinnerAnchor extends Widget
{
    public $caption = '';
    public $url = '';
    public $options = '';
    public $timeout = 0;

    public function init()
    {
        parent::init();
        if (empty($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        $this->registerAssets();
    }

    public function run()
    {
        $content = Html::a($this->caption, $this->url, $this->options);
        return $content;
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();

        $id = $this->options['id'];
        $js = '';

        $js .= "jQuery('#$id').click(function() {";
        $js .= "$(this).html('" . $this->caption . ' ' . FontAwesome::icon('cog')->spin() . "');";
        $js .= "$(this).attr('disabled','true');";
        $js .= '});';

        $view->registerJs($js);
    }
}