<?php

namespace app\components;

use yii;
use PHPMailer\PHPMailer\PHPMailer;

class Utils
{

    // Function to calculate square of value - mean
    public static function square($x, $mean)
    {
        return pow($x - $mean, 2);
    }

// Function to calculate standard deviation (uses square)
    public static function standard_deviation($array)
    {

// square root of sum of squares devided by N-1
        return sqrt(array_sum(array_map("self::square", $array, array_fill(0, count($array), (array_sum($array) / count($array))))) / (count($array) - 1));
    }

    // Function to calculate variance (uses square)
    public static function variance($array)
    {

// square root of sum of squares devided by N-1
        return sqrt(array_sum(array_map("self::square", $array, array_fill(0, count($array), (array_sum($array) / count($array))))) / count($array));
    }

    public static function absolute_mean($array)
    {
        if (count($array) == 0) {
            return 0;
        }

        $sum = 0;
        foreach ($array as $element) {
            $sum += abs($element);
        }

        return $sum / count($array);
    }

    public static function productivityText($productivity, $meanProductivity, $deltaProductivity)
    {
        if ($productivity < $meanProductivity) {
            if ($productivity < $meanProductivity - $deltaProductivity) {
                return 'Baja';
            } else {
                return 'Media baja';
            }
        } else {
            if ($productivity <= $meanProductivity + $deltaProductivity) {
                return 'Media alta';
            } else {
                return 'Alta';
            }
        }
    }

    public static function newMailer()
    {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        //Server settings
        //$mail->SMTPDebug = 4;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->CharSet = "utf-8";
        $mail->Host = Yii::$app->params['email']['host'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = Yii::$app->params['email']['username'];                 // SMTP username
        $mail->Password = Yii::$app->params['email']['password'];                           // SMTP password
        $mail->Port = Yii::$app->params['email']['port'];                                    // TCP port to connect to
        if (isset(Yii::$app->params['email']['encryption'])) {
            $mail->SMTPSecure = Yii::$app->params['email']['encryption'];                            // Enable TLS encryption, `ssl` also accepted
        }

        return $mail;
    }

}

?>
