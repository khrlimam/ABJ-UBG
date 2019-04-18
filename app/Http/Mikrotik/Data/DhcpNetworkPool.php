<?php


namespace App\Http\Mikrotik\Data;


class DhcpNetworkPool
{

    private $dhcp, $pool, $network;

    public function __construct(DhcpServer $dhcpServer, DhcpNetwork $dhcpNetwork, Pool $pool)
    {
        $this->dhcp = $dhcpServer;
        $this->network = $dhcpNetwork;
        $this->pool = $pool;
    }

    public function getDhcpServer()
    {
        return $this->dhcp;
    }

    public function getNetwork()
    {
        return $this->network;
    }

    public function getPool()
    {
        return $this->pool;
    }
}