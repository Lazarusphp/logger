<?php
namespace LazarusPhp\Logger;
use LazarusPhp\Logger\Logger;
use LogicException;
use LazarusPhp\Logger\Level;

class FileLogger extends Logger
{ 
    protected string $file = "";

    public function __construct(string $file)
    {
        if(!is_string($file))
        {
            throw new LogicException("Must be a file");
        }

        $this->file = $file;
    }

    private function isHandling($level,$message,$context)
    {
        return match ($level) {
            Level::Emergency => $this->emergency($message, $context),
            Level::Alert     => $this->alert($message, $context),
            Level::Critical  => $this->critical($message, $context),
            Level::Error     => $this->error($message, $context),
            Level::Warning   => $this->warning($message, $context),
            Level::Notice    => $this->notice($message, $context),
            Level::Info      => $this->info($message, $context),
            Level::Debug     => $this->debug($message, $context),
        };
    }

}