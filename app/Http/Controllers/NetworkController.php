<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dhcpNetworks = Auth::user()->mikrotik()->run("ip dhcp-server network print");
        return view('auth.network.index', compact('dhcpNetworks'));
    }
    
}
