<?php
namespace LazarusPhp\Logger;

use LogicException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


abstract class Logger implements LoggerInterface
{
    protected string $file = "";

    final public function getFile()
    {
        return $this->file;
    }

    private function interpolate(string $message,array $context):string
    {
        // Set new Array for map replacment
        $replace = [];

        // Loop Context
        foreach($context as $key => $value)
        {
        // Validate if it is a scaler type string, int or float
            if(is_scalar($value))
            {
            // Create a new Array
                $replace['{'.$key.'}'] = $value;
            }
        }

        // return new Mapping
        return strtr($message,$replace);
    }

    private function normalise(array $context)
    {
        foreach ($context as $key => $value) {
            if ($value instanceof \Throwable) {
                $context[$key] = [
                    'type' => get_class($value),
                    'message' => $value->getMessage(),
                    'code' => $value->getCode(),
                    'file' => $value->getFile(),
                    'line' => $value->getLine(),
                ];
            }
        }
    
        return $context;
    }
    // Log Levels below

    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        $date = date("d/m/y H:i:s");

        $message = $this->interpolate($message,$context);
        $context = $this->normalise($context);
        $line = sprintf(
        "[%s] %s %s %s%s",
        $date,
        strtoupper($level),
        $message,
        $context ? json_encode($context, JSON_UNESCAPED_SLASHES) : '',
        PHP_EOL
    );

    file_put_contents($this->file, $line, FILE_APPEND | LOCK_EX);
    }

}