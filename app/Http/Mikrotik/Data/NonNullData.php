<?php


namespace App\Http\Mikrotik\Data;


abstract class NonNullData
{

    abstract function getData(): array;

    public function getNonEmptyValue()
    {
        return collect($this->getData())->filter(function ($data) {
            return !empty($data);
        })->toArray();
    }

}