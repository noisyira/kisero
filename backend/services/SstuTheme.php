<?php
/**
 * Created by PhpStorm.
 * User: Уракин_ДВ
 * Date: 14.02.2019
 * Time: 15:19
 */

namespace backend\services;


class SstuTheme
{
    public static function Get($theme)
    {
        $value = null;

        switch ($theme)
        {
            case "ж 4":
            case "ж 4.1":
            case "ж 4.2":
            case "ж 5":
            case "ж 7":
            case "ж 8":
            case "ж 9":
            case "ж 15":
            case "ж 15.1":
            case "ж 15.2":
            case "ж 17":
            case "ж 17.1":
                $value = "0002.0014.0143.0389";
                break;
            case "ж 10":
            case "ж 11":
            case "ж 12":
            case "ж 13":
            case "ж 13.1":
            case "ж 13.2":
                $value = "0002.0014.0143.0390";
                break;
            case "ж 3":
            case "ж 6":
            case "ж 6.1":
            case "ж 6.2":
            case "ж 6.3":
            case "ж 14":
            case "ж 16":
                $value = "0002.0014.0143.0418";
                break;
            default:
                $value = "0002.0014.0143.0389";
                break;

        }
        return $value;
    }
}