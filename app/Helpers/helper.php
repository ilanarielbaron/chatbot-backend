<?php
use Illuminate\Support\Facades\Http;

function convertCurrency($from, $to, $amount){
    return $amount * 2; //The basic plan does not support conversion
}

function validateCurrency($currencyCode=""){
    $currencies = sendRequest('latest');

    return array_key_exists($currencyCode,$currencies);
}

function sendRequest($request){
    $response = Http::get('http://data.fixer.io/api/latest?access_key=68bc996353fb7cb47afc59f72287e54e');
    return $response["rates"];
}
