<?php declare(strict_types=1);

namespace TclTk\Widgets;

use InvalidArgumentException;
use TclTk\Options;

/**
 * Implementation of Tk button widget.
 *
 * @link https://www.tcl.tk/man/tcl8.6/TkCmd/button.htm
 *
 * @property string $text
 * @property string $default
 * @property string $state
 * @property string $overRelief
 * @property callable $command
 * @property int $height
 * @property int $width
 */
class Button extends Widget
{
    public function __construct(TkWidget $parent, string $title, array $options = [])
    {
        $options['text'] = $title;
        parent::__construct($parent, 'button', 'b', $options);
    }

    /**
     * @inheritdoc
     */
    protected function initOptions(): Options
    {
        return parent::initOptions()->mergeAsArray([
            'command' => null,
            'default' => null,
            'height' => null,
            'overRelief' => null,
            'state' => null,
            'width' => null,
        ]);
    }

    /**
     * Wrapper for 'command' property.
     */
    public function onClick(callable $callback): self
    {
        $this->command = $callback;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function __set(string $name, $value)
    {
        if ($name === 'command') {
            if (is_callable($value)) {
                $value = $this->window()->registerCallback($this, $value);
            } else {
                throw new InvalidArgumentException(sprintf('"%s" is not a valid button command.', $value));
            }
        }
        parent::__set($name, $value);
    }

    /**
     * Flash the button.
     *
     * @link http://www.tcl.tk/man/tcl8.6/TkCmd/button.htm#M16
     */
    public function flash(): void
    {
        $this->exec('flash');
    }

    /**
     * Manually click the button.
     *
     * @link http://www.tcl.tk/man/tcl8.6/TkCmd/button.htm#M17
     */
    public function invoke(): void
    {
        $this->exec('invoke');
    }

    public function isEnabled(): bool
    {
        return $this->state !== WidgetOptions::STATE_DISABLED;
    }
}