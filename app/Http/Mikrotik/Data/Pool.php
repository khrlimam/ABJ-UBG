<?php


namespace App\Http\Mikrotik\Data;


class Pool
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

    public function getName()
    {
        return $this->data['name'];
    }

    public function getRanges()
    {
        return collect(explode(',', $this->data['ranges']))->map(function ($range) {
            return explode("-", $range);
        })->toArray();
    }

}