<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NewDhcpSetup extends FormRequest
{

    public static $poolName = 'pool-name';
    public static $poolRangeBegin = 'pool-range-begin';
    public static $poolRangeEnd = 'pool-range-end';
    public static $networkAddress = 'network-address';
    public static $networkDefaultGateway = "network-default-gateway";
    public static $networkDns = "network-dns";
    public static $networkDomainName = "network-domain-name";
    public static $dhcpInterface = "dhcp-interface";
    public static $dhcpLeaseTime = "dhcp-lease-time";
    public static $dhcpStatus = "dhcp-status";

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            static::$poolName => 'required|string',
            static::$poolRangeBegin => 'required|ipv4',
            static::$poolRangeEnd => 'required|ipv4',
            static::$networkAddress => 'required',
            static::$networkDefaultGateway => 'required|ipv4',
            static::$networkDns => 'required|array|min:1',
            static::$networkDns.'.*' => 'distinct|nullable|ipv4',
            static::$dhcpInterface => 'required|string',
            static::$dhcpStatus => 'required'
        ];
    }

    public function attributes()
    {
        return [
            static::$poolName => 'IP pool name',
            static::$poolRangeBegin => 'IP pool start range ',
            static::$poolRangeEnd => 'IP pool end range',
            static::$networkAddress => 'network address',
            static::$networkDefaultGateway => 'default gateway',
            static::$networkDns.'.*' => 'DNS servers',
            static::$networkDomainName => 'domain name',
            static::$dhcpInterface => 'interface',
            static::$dhcpLeaseTime => 'lease time',
            static::$dhcpStatus => 'status'
        ];
    }

}
