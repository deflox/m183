<?php

/**
 * Provides functions for interacting with the monolog logging library.
 *
 * @author Leo Rudin
 */

namespace App\Libraries;

use App\Accessor;

class Logger extends Accessor
{
    /**
     * Logs a validation error message.
     *
     * @param $message
     * @param $class
     * @param $method
     * @param $line
     */
    public function logValErr($message, $class, $method, $line)
    {
        $this->log->info('Validation error occured in class "'.$class.'" in method "'.$method.'" at line "'.$line.'" with message: '.$message);
    }

    /**
     * Logs an entering to a method.
     *
     * @param $class
     * @param $method
     * @param $line
     */
    public function logMethod($class, $method, $line)
    {
        $this->log->info('User entered method "'.$method.'" in "'.$class.'" at line "'.$line.'"');
    }
}