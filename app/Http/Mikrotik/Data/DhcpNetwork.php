<?php


namespace App\Http\Mikrotik\Data;


class DhcpNetwork extends NonNullData
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->data['.id'];
    }

    public function getAddress()
    {
        return $this->data['address'];
    }

    public function getGateway()
    {
        return $this->data['gateway'];
    }

    public function getDnsServers()
    {
        return explode(',', $this->data['dns-server']);
    }

    public function getDomain()
    {
        return key_exists('domain', $this->data) ? $this->data['domain'] : '-';
    }


}