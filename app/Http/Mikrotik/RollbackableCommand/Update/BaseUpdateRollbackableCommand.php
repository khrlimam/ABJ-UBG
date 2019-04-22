<?php


namespace App\Http\Mikrotik\RollbackableCommand\Update;


use App\Http\Mikrotik\RollbackableCommand\BaseValidStateRequiredRollbackableCommand;
use App\Http\Mikrotik\Util\Operation;
use Exception;
use KhairulImam\ROSWrapper\Wrapper as Mikrotik;

abstract class BaseUpdateRollbackableCommand extends BaseValidStateRequiredRollbackableCommand
{


    /**
     * @var array
     */
    private $newState;

    public function __construct(Mikrotik $mikrotik, $currentState = [], $newState = [])
    {
        parent::__construct($mikrotik, $currentState);
        $this->newState = $newState;
        $this->newState['.id'] = $currentState['.id'];
    }

    protected function getInvalidKeys(): array
    {
        return ['invalid'];
    }

    public function run()
    {
        $this->operation = $this->mikrotik->run($this->getRunCommand(), $this->newState);
        if (!Operation::isSuccess($this->operation)) {
            $reason = "";
            if (is_array($this->operation)
                && key_exists('!trap', $this->operation)) $reason = $this->operation["!trap"][0]['message'];
            throw new Exception($reason);
        }
    }
}