<?php

class Utils
{
    public static function h($s)
    {
        return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }
}