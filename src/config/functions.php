<?php

/**
 * @throws Exception
 */
function gerarString($tamanho, $alfa = true, $numeros = false, $especiais = false, $minusculas = false, $maiusculas = false)
{
    $chars = '';
    $sMinusculas = 'abcdefghijklmnopqrstuvwxyz';
    $sMaiusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $sNumeros = '1234567890';
    $sEspeciais = '!@#$%*-';
    $string = '';
    if ($alfa) {
        $chars .= $sMinusculas . $sMaiusculas;
    } else {
        if ($minusculas) {
            $chars .= $sMinusculas;
        }
        if ($maiusculas) {
            $chars .= $sMaiusculas;
        }
    }
    if ($numeros) {
        $chars .= $sNumeros;
    }
    if ($especiais) {
        $chars .= $sEspeciais;
    }
    for ($i = 1; $i <= $tamanho; $i++) {
//        $string .= $chars[(mt_rand(0, strlen($chars))) - 1];
//        $string .= $chars[(sodium_randombytes_uniform(0, strlen($chars)))];
        $string .= $chars[(random_int(0, strlen($chars))) - 1];
//        $string .= $chars[(random_bytes(12))];
    }
    return $string;
}

function limpaMascara($string)
{
    $string = str_replace(['.', '-', '/', '(', ')', ' '], '', $string);
    return $string;
}