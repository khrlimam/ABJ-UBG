<?php


namespace App\Http\Mikrotik\RollbackableCommand\Delete;


use App\Http\Mikrotik\Util\Operation;
use Exception;
use Illuminate\Support\Arr;
use KhairulImam\ROSWrapper\RollbackableCommand;
use KhairulImam\ROSWrapper\Wrapper as Mikrotik;

abstract class BaseDeleteRollbackableCommand extends RollbackableCommand
{

    private $mikrotik;
    /**
     * @var array
     */
    private $currentState;
    /**
     * @var array
     */
    private $operation;

    public function __construct(Mikrotik $mikrotik, $currentState = [])
    {
        $this->mikrotik = $mikrotik;
        $this->currentState = $currentState;
    }

    /**
     * @throws Exception
     */
    function run()
    {
        $this->operation = $this->mikrotik->run($this->getRunCommand(), ['.id' => $this->currentState['.id']]);
        if (!Operation::isSuccess($this->operation)) {
            $reason = "";
            if (is_array($this->operation)
                && key_exists('!trap', $this->operation)) $reason = $this->operation["!trap"][0]['message'];
            throw new Exception($reason);
        }
    }

    /**
     * @return string
     * command that will be ran
     */
    public abstract function getRunCommand();

    public function rollback()
    {
        if (Operation::isSuccess($this->operation)) {
            $stateWithoutId = Arr::except($this->currentState, ['.id', 'invalid']);
            $acceptableState = Operation::transformStateIntoAcceptableCommand($stateWithoutId);
            $this->mikrotik->run($this->getRollbackCommand(), $acceptableState);
        }
    }

    /**
     * @return string
     */
    public abstract function getRollbackCommand();
}