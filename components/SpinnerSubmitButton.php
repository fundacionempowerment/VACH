<?php

namespace app\components;


use rmrevin\yii\fontawesome\FontAwesome;
use yii\base\Widget;
use yii\helpers\Html;

class SpinnerSubmitButton extends Widget
{
    public $caption = '';
    public $options = '';

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
        $content = Html::submitButton($this->caption, $this->options);
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
        if (!YII_ENV_TEST) {
            $js .= "$(this).attr('disabled','disabled');";
        }
        $js .= '});';

        $js .= "jQuery('#$id').parents('form:first').on('afterValidate', function(event, messages, errorAttributes) {";
        $js .= "if (errorAttributes.length > 0) {";
        $js .= "$('#$id').html('" . $this->caption . "');";
        $js .= "$('#$id').removeAttr('disabled');";
        $js .= '}});';

        $view->registerJs($js);
    }
}