<?php
namespace LazarusPhp\Logger;

enum Level:int
{
    case Emergency = 600;
    case Alert     = 550;
    case Critical  = 500;
    case Error     = 400;
    case Warning   = 300;
    case Notice    = 250;
    case Info      = 200;
    case Debug     = 100;


    public function label():string
    {
        return strtoupper($this->name);
    }
}