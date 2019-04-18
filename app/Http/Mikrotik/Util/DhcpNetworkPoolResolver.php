<?php


namespace App\Http\Mikrotik\Util;


use App\Http\Mikrotik\Data\DhcpNetwork;
use App\Http\Mikrotik\Data\DhcpNetworkPool;
use App\Http\Mikrotik\Data\DhcpServer;
use App\Http\Mikrotik\Data\Pool;
use Illuminate\Contracts\Auth\Guard;
use Symfony\Component\HttpFoundation\IpUtils;

class DhcpNetworkPoolResolver
{


    private $mikrotik;

    public function __construct(Guard $guard)
    {
        $this->mikrotik = $guard->user()->mikrotik();
    }

    public function resolveForDhcpId($id)
    {
        $detailDhcpServer = head($this->mikrotik->run("ip dhcp-server print", ["?.id" => $id]));
        $dhcp = new DhcpServer($detailDhcpServer);

        $detailPool = head($this->mikrotik->run("ip pool print", ['?name' => $dhcp->getAddressPool()]));
        $pool = new Pool($detailPool);
        $firstRangeIp = head(head($pool->getRanges()));

        $networkCollections = collect($this->mikrotik->run("ip dhcp-server network print"));
        $matchedPoolNetworkAddress = $networkCollections->filter(function ($network) use ($firstRangeIp) {
            return IpUtils::checkIp($firstRangeIp, $network['address']);
        })->first();
        $network = new DhcpNetwork($matchedPoolNetworkAddress);

        return new DhcpNetworkPool($dhcp, $network, $pool);
    }

}