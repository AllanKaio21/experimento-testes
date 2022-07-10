<?php
/**
 * Created by PhpStorm.
 * User: Peterson
 * Date: 21/05/2019
 * Time: 15:36
 */
namespace app\extensions;

class Mask
{
    public static function cpf($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}