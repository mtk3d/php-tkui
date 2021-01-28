<?php declare(strict_types=1);

namespace TclTk\Widgets\Ttk;

use TclTk\Options;
use TclTk\Variable;
use TclTk\Widgets\Widget;

/**
 * @link https://www.tcl.tk/man/tcl8.6/TkCmd/ttk_checkbutton.htm
 *
 * @property string $text
 * @property Variable $variable
 * @property string|int|float|bool $onValue
 * @property string|int|float|bool $offValue
 *
 * @todo Implement $onValue and $offValue properties.
 */
class CheckButton extends SwitchableButton
{
    /**
     * @inheritdoc
     */
    public function __construct(Widget $parent, string $title, bool $initialState = false, array $options = [])
    {
        $options['text'] = $title;

        parent::__construct($parent, 'ttk::checkbutton', 'chk', $options);

        $this->variable->set($initialState);
    }

    /**
     * @inheritdoc
     */
    protected function initWidgetOptions(): Options
    {
        return parent::initWidgetOptions()->mergeAsArray([
            'offValue' => null,
            'onValue' => null,
        ]);
    }
}