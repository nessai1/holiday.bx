<?php


class Router
{
    public static function redirect(string $link, array $getParams = array()) : void
    {
        $resultLink = $link;
        if (!count($getParams))
        {
            foreach ($getParams as $key => $value)
            {
                $resultLink .= "{$key}={$value}&";
            }

            $resultLink = substr($resultLink, 0, -1);
        }
        header("Location: {$resultLink}");
        exit();
    }
}