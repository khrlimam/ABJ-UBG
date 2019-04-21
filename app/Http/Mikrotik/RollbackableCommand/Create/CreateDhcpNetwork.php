<?php


namespace App\Http\Mikrotik\RollbackableCommand\Create;


class CreateDhcpNetwork extends BaseCreateRollbackableCommand
{
    /**
     * @return string
     */
    function name()
    {
        return "Create DHCP Network";
    }

    /**
     * @return string
     * command that will be ran
     */
    public function getRunCommand()
    {
        return "ip dhcp-server network add";
    }

    /**
     * @return string
     */
    public function getRollbackCommand()
    {
        return "ip dhcp-server network remove";
    }
}