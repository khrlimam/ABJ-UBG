<?php


namespace App\Http\Mikrotik\RollbackableCommand\Create;


use App\Http\Mikrotik\Util\Operation;
use KhairulImam\ROSWrapper\RollbackableCommand;
use KhairulImam\ROSWrapper\Wrapper as Mikrotik;
use Exception;

/**
 * @property  mikrotik
 */
abstract class BaseCreateRollbackableCommand extends RollbackableCommand
{

    private $mikrotik;
    /**
     * @var array
     */
    private $newData;
    protected $id = "";

    public function __construct(Mikrotik $mikrotik, $newData = [])
    {
        $this->mikrotik = $mikrotik;
        $this->newData = $newData;
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

    /**
     * @throws Exception
     */
    function run()
    {
        $this->id = $this->mikrotik->run($this->getRunCommand(), $this->newData);
        if (!Operation::isSuccess($this->id)) {
            $reason = "";
            if (is_array($this->id)
                && key_exists('!trap', $this->id)) $reason = $this->id["!trap"][0]['message'];
            throw new Exception($reason);
        }
    }

    public function rollback()
    {
        if (Operation::isSuccess($this->id))
            $this->mikrotik->run($this->getRollbackCommand(), ['.id' => $this->id]);
    }
}