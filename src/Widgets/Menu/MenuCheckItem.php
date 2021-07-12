<?php declare(strict_types=1);

namespace PhpGui\Widgets\Menu;

use PhpGui\Options;
use PhpGui\Exceptions\UninitializedVariableException;
use PhpGui\TclTk\Variable;
use PhpGui\Widgets\Common\ValueInVariable;
use PhpGui\Widgets\TkWidget;
use SplObserver;

/**
 * @property string $label
 * @property callable $command
 * @property Variable $variable
 * @property int $underline
 */
class MenuCheckItem extends MenuItem implements ValueInVariable
{
    private bool $value;

    /**
     * @param callable|null $callback
     */
    public function __construct(string $label, bool $value, $callback = null, array $options = [])
    {
        parent::__construct($label, $callback, $options);
        $this->variable = $options['variable'] ?? null;
        $this->setValue($value);
    }

    /**
     * @inheritdoc
     */
    public function type(): string
    {
        return 'checkbutton';
    }

    /**
     * @inheritdoc
     */
    protected function createOptions(): Options
    {
        return new Options([
            'label' => null,
            'command' => null,
            'variable' => null,
            'underline' => null,
        ]);
    }

    /**
     * @param bool $value
     */
    public function setValue($value): self
    {
        $this->value = (bool) $value;
        // TODO: PHP8: $this->variable?->set($value)
        if ($this->variable) {
            $this->variable->set($value);
            $this->notify();
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        if (! $this->variable) {
            throw new UninitializedVariableException();
        }
        return $this->variable->asBool();
    }

    public function attach(SplObserver $observer)
    {
        parent::attach($observer);
        if ($observer instanceof TkWidget && ! $this->variable) {
            $this->variable = $observer->parent()->getEval()->registerVar($this->makeVariableName());
            $this->setValue($this->value);
        }
    }

    protected function makeVariableName(): string
    {
        return 'chk-item-' . $this->id();
    }
}