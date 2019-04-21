<?php


namespace App\Http\Mikrotik\RollbackableCommand\Create;


use App\Http\Mikrotik\Util\Operation;
use KhairulImam\ROSWrapper\RollbackableCommand;
use KhairulImam\ROSWrapper\Wrapper as Mikrotik;
use Exception;

/**
 * @property  mikrotik
 */
class CreatePool extends BaseCreateRollbackableCommand
{
    /**
     * @return string
     * command that will be ran
     */
    public function getRunCommand()
    {
        return 'ip pool add';
    }

    /**
     * @return string
     */
    function name()
    {
        return 'Create IP Pool';
    }

    /**
     * @return string
     */
    public function getRollbackCommand()
    {
        return 'ip pool remove';
    }
}