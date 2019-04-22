<?php


namespace App\Http\Mikrotik\RollbackableCommand\Update;


class UpdatePool extends BaseUpdateRollbackableCommand
{
    /**
     * @return string
     * command that will be ran
     */
    public function getRunCommand()
    {
        return "ip pool set";
    }

    /**
     * @return string
     */
    public function getRollbackCommand()
    {
        return "ip pool set";
    }

    /**
     * @return string
     */
    function name()
    {
        return 'Update IP Pool';
    }
}