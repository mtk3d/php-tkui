<?php declare(strict_types=1);

namespace PhpGui\TclTk;

use FFI\CData;
use PhpGui\HasLogger;
use PhpGui\TclTk\Exceptions\TclException;
use PhpGui\TclTk\Exceptions\TclInterpException;

/**
 * Tcl interpreter implementation.
 */
class Interp
{
    use HasLogger;

    private Tcl $tcl;
    private CData $interp;

    public function __construct(Tcl $tcl, CData $interp)
    {
        $this->tcl = $tcl;
        $this->interp = $interp;
    }

    /**
     * Initializes Tcl interpreter.
     */
    public function init(): void
    {
        $this->debug('init');
        $this->tcl->init($this);
    }

    public function cdata(): CData
    {
        return $this->interp;
    }

    /**
     * Gets the string result of the last executed command.
     */
    public function getStringResult(): string
    {
        return $this->tcl->getStringResult($this);
    }

    /**
     * Evaluates a Tcl script.
     */
    public function eval(string $script)
    {
        $this->debug('eval', ['script' => $script]);
        $this->tcl->eval($this, $script);
    }

    public function tcl(): Tcl
    {
        return $this->tcl;
    }

    /**
     * Creates a Tcl command.
     */
    public function createCommand(string $command, callable $callback)
    {
        $this->debug('createCommand', ['command' => $command]);
        $this->tcl->createCommand($this, $command, function ($data, $interp, $objc, $objv) use ($callback) {
            $params = [];
            for ($i = 1; $i < $objc; $i ++) {
                $params[] = $this->tcl->getString($objv[$i]);
            }
            $callback(...$params);
        });
    }

    /**
     * @throws TclInterpException When the command delete failed.
     */
    public function deleteCommand(string $command): void
    {
        $this->debug('deleteCommand', ['command' => $command]);
        $this->tcl->deleteCommand($this, $command);
    }

    /**
     * Creates a Tcl variable instance.
     *
     * @param mixed $value
     *
     * @throws TclException
     * @throws TclInterpException
     */
    public function createVariable(string $varName, ?string $arrIndex = NULL, $value = NULL): Variable
    {
        $this->debug('createVariable', [
            'varName' => $varName,
            'arrIndex' => $arrIndex,
            'value' => $value,
        ]);
        return new Variable($this, $varName, $arrIndex, $value);
    }

    /**
     * Gets the interp eval result as a list of strings.
     */
    public function getListResult(): array
    {
        return $this->tcl->getListResult($this);
    }
}