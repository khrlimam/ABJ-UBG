<?php


namespace App\Http\Mikrotik\RollbackableCommand\Delete;

class DeletePool extends BaseDeleteRollbackableCommand
{
    /**
     * @return string
     * command that will be ran
     */
    public function getRunCommand()
    {
        return 'ip pool remove';
    }

    /**
     * @return string
     */
    public function getRollbackCommand()
    {
        return 'ip pool add';
    }

    /**
     * @return string
     */
    function name()
    {
        return 'Delete IP Pool';
    }
}