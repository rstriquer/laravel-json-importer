<?php

namespace App\Traits;

trait ValidateUTF8File
{
    /**
     * Checks if file is market as UTF8 encoding by the filesystem
     * - uses "exec" php function
     * @param string $fileName
     * @return bool
     */
    public function isUTF8File(string $fileName)
    {

        if ($fileName == null) {
            return false;
        }
        if (!is_file($fileName)) {
            return false;
        }
        $output = array();
        exec('file -i ' . $fileName, $output);
        list(,$ex) = explode('charset=', $output[0]);
        $ex = strtoupper($ex);
        return in_array($ex, ['UTF-8', 'ASCII', 'US-ASCII', 'WINDOWS-1252', 'WINDOWS-1254']);
    }
}
