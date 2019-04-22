<?php


namespace App\Http\Mikrotik\Data;


class DhcpServer extends NonNullData
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

    public function getAddressPool()
    {
        return $this->data['address-pool'];
    }

    public function getId()
    {
        return $this->data['.id'];
    }

    public function getName()
    {
        return $this->data['name'];
    }

    public function getInterface()
    {
        return $this->data['interface'];
    }

    public function getLeaseTime()
    {
        return $this->data['lease-time'];
    }

    public function isInvalid()
    {
        return 'false' === $this->data['invalid'];
    }

    public function isDisabled()
    {
        return 'true' === $this->data['disabled'];
    }


}