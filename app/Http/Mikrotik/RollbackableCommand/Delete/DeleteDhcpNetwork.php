<?php


namespace App\Http\Mikrotik\RollbackableCommand\Delete;


class DeleteDhcpNetwork extends BaseDeleteRollbackableCommand
{
    /**
     * @return string
     * command that will be ran
     */
    public function getRunCommand()
    {
        return 'ip dhcp-server network remove';
    }

    /**
     * @return string
     */
    public function getRollbackCommand()
    {
        return 'ip dhcp-server network add';
    }

    /**
     * @return string
     */
    function name()
    {
        return 'Delete DHCP Server Network';
    }
}