<?php


namespace App\Http\Mikrotik\RollbackableCommand;


use App\Http\Mikrotik\Util\Operation;
use Illuminate\Support\Arr;
use KhairulImam\ROSWrapper\RollbackableCommand;
use KhairulImam\ROSWrapper\Wrapper as Mikrotik;

abstract class BaseValidStateRequiredRollbackableCommand extends RollbackableCommand
{

    protected $mikrotik;
    /**
     * @var array
     */
    protected $currentState;
    /**
     * @var array
     */
    protected $operation;

    public function __construct(Mikrotik $mikrotik, $currentState = [])
    {
        $this->mikrotik = $mikrotik;
        $this->currentState = $currentState;
    }

    /**
     * @return string
     * command that will be ran
     */
    public abstract function getRunCommand();

    /**
     * @return string
     */
    public abstract function getRollbackCommand();

    protected function getInvalidKeys(): array
    {
        return ['.id', 'invalid'];
    }

    public function rollback()
    {
        if (Operation::isSuccess($this->operation)) {
            $validState = Arr::except($this->currentState, $this->getInvalidKeys());
            $acceptableState = Operation::transformStateIntoAcceptableCommand($validState);
            $this->mikrotik->run($this->getRollbackCommand(), $acceptableState);
        }
    }
}