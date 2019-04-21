<?php


namespace App\Http\Mikrotik\RollbackableCommand\Create;

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