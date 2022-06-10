<?php

namespace app\components;

use yii;

class Downloader
{

    public static function download($url, $sesionId = null)
    {
        $url .= '&t=' . ($sesionId ?: session_id());

        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $request = curl_exec($process);

        if(curl_error($process))
        {
            echo 'error:' . curl_error($process);
            die();
        }

        curl_close($process);

        $name = uniqid('', true) . '.png';

        $file = Yii::getAlias('@runtime/temp/' . $name);

        file_put_contents($file, $request);

        return $file;
    }

}
