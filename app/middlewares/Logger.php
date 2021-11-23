<?php

class Logger
{
    public static function LogOperacion($request, $handler)
    {
        // $retorno = $next($request, $response);

        $reponse = $handler->handle($request);

        // return $retorno;
        return $response;
    }



}