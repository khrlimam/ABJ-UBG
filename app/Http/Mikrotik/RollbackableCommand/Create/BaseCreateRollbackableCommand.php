<?php


namespace App\Http\Mikrotik\RollbackableCommand\Create;


use App\Http\Mikrotik\Util\Operation;
use Exception;
use KhairulImam\ROSWrapper\RollbackableCommand;
use KhairulImam\ROSWrapper\Wrapper as Mikrotik;

abstract class BaseCreateRollbackableCommand extends RollbackableCommand
{

    protected $id = "";
    private $mikrotik;
    /**
     * @var array
     */
    private $newData;

    public function __construct(Mikrotik $mikrotik, $newData = [])
    {
        $this->mikrotik = $mikrotik;
        $this->newData = $newData;
    }

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

    /**
     * @return string
     * command that will be ran
     */
    public abstract function getRunCommand();

    public function rollback()
    {
        if (Operation::isSuccess($this->id))
            $this->mikrotik->run($this->getRollbackCommand(), ['.id' => $this->id]);
    }

    /**
     * @return string
     */
    public abstract function getRollbackCommand();
}