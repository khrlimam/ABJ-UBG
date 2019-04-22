<?php


namespace App\Http\Mikrotik\RollbackableCommand\Delete;


use App\Http\Mikrotik\RollbackableCommand\BaseValidStateRequiredRollbackableCommand;
use App\Http\Mikrotik\Util\Operation;
use Exception;

abstract class BaseDeleteRollbackableCommand extends BaseValidStateRequiredRollbackableCommand
{

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

}