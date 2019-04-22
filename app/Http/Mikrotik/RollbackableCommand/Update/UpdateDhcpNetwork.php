<?php


namespace App\Http\Mikrotik\RollbackableCommand\Update;


class UpdateDhcpNetwork extends BaseUpdateRollbackableCommand
{
    /**
     * @return string
     * command that will be ran
     */
    public function getRunCommand()
    {
        return "ip dhcp-server network set";
    }

    /**
     * @return string
     */
    public function getRollbackCommand()
    {
        return "ip dhcp-server network set";
    }

    /**
     * @return string
     */
    function name()
    {
        return 'Update DHCP Server Network';
    }
}