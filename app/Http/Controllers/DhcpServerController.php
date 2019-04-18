<?php

namespace App\Http\Controllers;

use App\Http\Mikrotik\Util\DhcpNetworkPoolResolver;
use App\Http\Requests\NewDhcpSetup;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DhcpServerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dhcpServers = Auth::user()->mikrotik()->run("ip dhcp-server print");
        return view('auth.dhcp-server.index', compact('dhcpServers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $mikrotik = Auth::user()->mikrotik();

        $usedInterfaces = collect($mikrotik->run("ip dhcp-server print"))->map(function ($item) {
            return $item['interface'];
        })->toArray();
        $interfaces = collect($mikrotik->run("interface print"))->filter(function ($item) use ($usedInterfaces) {
            return !in_array($item['name'], $usedInterfaces);
        });
        return view('auth.dhcp-server.create', compact('interfaces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(NewDhcpSetup $request)
    {
        $validated = $request->validated();
        $mikrotik = Auth::user()->mikrotik();

        $addIpPool = $mikrotik->run("ip pool add", [
            'name' => $validated[NewDhcpSetup::$poolName],
            'ranges' => $validated[NewDhcpSetup::$poolRangeBegin] . '-' . $validated[NewDhcpSetup::$poolRangeEnd]
        ]);

        $addDhcpNetwork = $mikrotik->run("ip dhcp-server network add", [
            'address' => $validated[NewDhcpSetup::$networkAddress],
            'gateway' => $validated[NewDhcpSetup::$networkDefaultGateway],
            'dns-server' => static::commaSeparated($validated[NewDhcpSetup::$networkDns]),
            'domain' => $request->input(NewDhcpSetup::$networkDomainName)
        ]);

        $addDhcpServer = $mikrotik->run("ip dhcp-server add", [
            'address-pool' => $validated[NewDhcpSetup::$poolName],
            'interface' => $validated[NewDhcpSetup::$dhcpInterface],
            'lease-time' => $request->input(NewDhcpSetup::$dhcpLeaseTime),
            'disabled' => $validated[NewDhcpSetup::$dhcpStatus]
        ]);

        if (static::isSuccess($addIpPool)
            && static::isSuccess($addDhcpNetwork)
            && static::isSuccess($addDhcpServer))
            return redirect()->route('dhcp-server.show', $addDhcpServer)->with('status', 'Berhasil mengkonfigurasi DHCP Server baru!');
        else return redirect()->back()->with('fail', 'Mohon maaf, terjadi kesalahan pada aktivitas anda')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param DhcpNetworkPoolResolver $dhcpNetworkPoolResolver
     * @return Response
     */
    public function show($id, DhcpNetworkPoolResolver $dhcpNetworkPoolResolver)
    {
        $resolver = $dhcpNetworkPoolResolver->resolveForDhcpId($id);
        $dhcp = $resolver->getDhcpServer();
        $network = $resolver->getNetwork();
        $pool = $resolver->getPool();
        return view('auth.dhcp-server.show', compact('dhcp', 'network', 'pool'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param DhcpNetworkPoolResolver $dhcpNetworkPoolResolver
     * @return Response
     */
    public function destroy($id, DhcpNetworkPoolResolver $dhcpNetworkPoolResolver)
    {
        $mikrotik = Auth::user()->mikrotik();
        $resolver = $dhcpNetworkPoolResolver->resolveForDhcpId($id);
        $dhcp = $resolver->getDhcpServer();
        $network = $resolver->getNetwork();
        $pool = $resolver->getPool();

        $deleteDhcpServer = $mikrotik->run("ip dhcp-server remove", ['.id' => $dhcp->getId()]);
        $deleteNetwork = $mikrotik->run("ip dhcp-server network remove", ['.id' => $network->getId()]);
        $deletePool = $mikrotik->run("ip pool remove", ['.id' => $pool->getId()]);

        if (self::isSuccess($deleteDhcpServer)
            && self::isSuccess($deleteNetwork)
            && self::isSuccess($deletePool)) {
            return redirect()->route('dhcp-server.index')->with('status', 'Data DHCP Server dengan checksum ' . $id . ' telah dihapus');
        }
    }

    public function toggle($id, $toggle)
    {
        $operation = Auth::user()->mikrotik()->run("ip dhcp-server set", [
            'disabled' => $toggle,
            '.id' => $id
        ]);
        if (count($operation) == 0) {
            return redirect()->route('dhcp-server.index')->with('status', 'Data DHCP Server dengan checksum ' . $id . ' telah diubah');
        }
    }

    public static function isSuccess($returned)
    {
        if (is_array($returned)) return count($returned) == 0;
        return FALSE != $returned;
    }

    public static function commaSeparated(array $data)
    {
        $nonNull = Arr::where($data, function ($v) {
            return !is_null($v);
        });
        return join(',', $nonNull);
    }

}
