<?php

namespace app\components;

class Downloader
{

    public static function download($url)
    {
        $request = file_get_contents($url . '&t=' . session_id());

        $name = uniqid() . '.png';
        $file = DIRECTORY_SEPARATOR .
                trim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) .
                DIRECTORY_SEPARATOR .
                ltrim($name, DIRECTORY_SEPARATOR);

        file_put_contents($file, $request);

        return $file;
    }

}
