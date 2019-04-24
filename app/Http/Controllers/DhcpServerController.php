<?php

namespace App\Http\Controllers;

use App\Http\Mikrotik\RollbackableCommand\Create\CreateDhcpNetwork;
use App\Http\Mikrotik\RollbackableCommand\Create\CreateDhcpServer;
use App\Http\Mikrotik\RollbackableCommand\Create\CreatePool;
use App\Http\Mikrotik\RollbackableCommand\Delete\DeleteDhcpNetwork;
use App\Http\Mikrotik\RollbackableCommand\Delete\DeleteDhcpServer;
use App\Http\Mikrotik\RollbackableCommand\Delete\DeletePool;
use App\Http\Mikrotik\RollbackableCommand\Update\UpdateDhcpNetwork;
use App\Http\Mikrotik\RollbackableCommand\Update\UpdateDhcpServer;
use App\Http\Mikrotik\RollbackableCommand\Update\UpdatePool;
use App\Http\Mikrotik\Util\DhcpNetworkPoolResolver;
use App\Http\Mikrotik\Util\Operation;
use App\Http\Requests\DhcpSetup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use KhairulImam\ROSWrapper\RollbackedException;
use KhairulImam\ROSWrapper\Sequential;

class DhcpServerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $mikrotik = Auth::user()->mikrotik();
        $dhcpServers = $mikrotik->run("ip dhcp-server print");
        $clientConnected = collect($mikrotik->run("ip dhcp-server lease print"))->groupBy('server');
        return view('auth.dhcp-server.index', compact('dhcpServers', 'clientConnected'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $interfaces = Operation::getUnusedInterfaceAlongWithIpAddress(Auth::user()->mikrotik());
        return view('auth.dhcp-server.create', compact('interfaces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(DhcpSetup $request)
    {
        $validated = $request->validated();
        $mikrotik = Auth::user()->mikrotik();

        $newPoolData = [
            'name' => $validated[DhcpSetup::$poolName],
            'ranges' => $validated[DhcpSetup::$poolRangeBegin] . '-' . $validated[DhcpSetup::$poolRangeEnd]
        ];

        $newNetworkData = [
            'address' => $validated[DhcpSetup::$networkAddress] . '/' . $validated[DhcpSetup::$networkSubnetMask],
            'gateway' => $validated[DhcpSetup::$networkDefaultGateway],
            'dns-server' => static::commaSeparated($validated[DhcpSetup::$networkDns]),
            'domain' => $request->input(DhcpSetup::$networkDomainName)
        ];

        $newDhcpServerData = [
            "name" => $this->validateDhcpNameOrDefault($request->input(DhcpSetup::$dhcpName), $validated[DhcpSetup::$poolName]),
            'address-pool' => $validated[DhcpSetup::$poolName],
            'interface' => $validated[DhcpSetup::$dhcpInterface],
            'lease-time' => $request->input(DhcpSetup::$dhcpLeaseTime),
            'disabled' => $validated[DhcpSetup::$dhcpStatus]
        ];


        try {
            $mikrotik->runSequentialProcess(Sequential::process(
                new CreatePool($mikrotik, $newPoolData),
                new CreateDhcpNetwork($mikrotik, $newNetworkData),
                $createDhcpServer = new CreateDhcpServer($mikrotik, $newDhcpServerData)
            ));
            return redirect()->route('dhcp-server.show', $createDhcpServer->getId())->with('status', 'Berhasil mengkonfigurasi DHCP Server baru!');
        } catch (RollbackedException $exception) {
            return redirect()->back()->with('fail', Operation::getPrettyMessage($exception))->withInput();
        }

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

        try {
            $mikrotik->runSequentialProcess(Sequential::process(
                new DeleteDhcpServer($mikrotik, $dhcp->getData()),
                new DeleteDhcpNetwork($mikrotik, $network->getData()),
                new DeletePool($mikrotik, $pool->getData())
            ));
            return redirect()->route('dhcp-server.index')->with('status', 'Data DHCP Server dengan checksum ' . $id . ' telah dihapus');
        } catch (RollbackedException $exception) {
            return redirect()->route('dhcp-server.index')->with('fail', Operation::getPrettyMessage($exception));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, DhcpNetworkPoolResolver $dhcpNetworkPoolResolver)
    {
        $interfaces = Operation::getAvailableInterfacesAlongWithIpAddresses(Auth::user()->mikrotik());
        $resolver = $dhcpNetworkPoolResolver->resolveForDhcpId($id);
        $dhcp = $resolver->getDhcpServer();
        $network = $resolver->getNetwork();
        $pool = $resolver->getPool();
        return view('auth.dhcp-server.edit', compact('interfaces', 'dhcp', 'network', 'pool'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DhcpSetup $request
     * @param int $id
     * @param DhcpNetworkPoolResolver $dhcpNetworkPoolResolver
     * @return void
     */
    public function update(DhcpSetup $request, $id, DhcpNetworkPoolResolver $dhcpNetworkPoolResolver)
    {
        $validated = $request->validated();
        $mikrotik = Auth::user()->mikrotik();

        $resolver = $dhcpNetworkPoolResolver->resolveForDhcpId($id);
        $dhcp = $resolver->getDhcpServer();
        $network = $resolver->getNetwork();
        $pool = $resolver->getPool();

        $newPoolData = [
            'name' => $validated[DhcpSetup::$poolName],
            'ranges' => $validated[DhcpSetup::$poolRangeBegin] . '-' . $validated[DhcpSetup::$poolRangeEnd]
        ];

        $newNetworkData = [
            'address' => $validated[DhcpSetup::$networkAddress] . '/' . $validated[DhcpSetup::$networkSubnetMask],
            'gateway' => $validated[DhcpSetup::$networkDefaultGateway],
            'dns-server' => static::commaSeparated($validated[DhcpSetup::$networkDns]),
            'domain' => $request->input(DhcpSetup::$networkDomainName)
        ];

        $newDhcpServerData = [
            "name" => $this->validateDhcpNameOrDefault($request->input(DhcpSetup::$dhcpName), $validated[DhcpSetup::$poolName]),
            'address-pool' => $validated[DhcpSetup::$poolName],
            'interface' => $validated[DhcpSetup::$dhcpInterface],
            'lease-time' => $request->input(DhcpSetup::$dhcpLeaseTime),
            'disabled' => $validated[DhcpSetup::$dhcpStatus]
        ];

        try {
            $mikrotik->runSequentialProcess(Sequential::process(
                new UpdatePool($mikrotik, $pool->getData(), $newPoolData),
                new UpdateDhcpNetwork($mikrotik, $network->getData(), $newNetworkData),
                new UpdateDhcpServer($mikrotik, $dhcp->getData(), $newDhcpServerData)
            ));
            return redirect()->route('dhcp-server.show', $dhcp->getId())->with('status', 'Data DHCP Server dengan checksum ' . $id . ' telah diupdate.');
        } catch (RollbackedException $exception) {
            return redirect()->back()->withInput()->with('fail', Operation::getPrettyMessage($exception));
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

    private function validateDhcpNameOrDefault($newName, $pool)
    {
        if (empty(trim($newName))) return 'dhcp_server_' . $pool;
        return $newName;
    }

    public static function commaSeparated(array $data)
    {
        $nonNull = Arr::where($data, function ($v) {
            return !is_null($v);
        });
        return join(',', $nonNull);
    }

}
