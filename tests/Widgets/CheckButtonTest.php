<?php declare(strict_types=1);

namespace TclTk\Tests\Widgets;

use TclTk\Tests\TestCase;
use TclTk\Widgets\Buttons\CheckButton;

class CheckButtonTest extends TestCase
{
    /** @test */
    public function widget_created()
    {
        $this->tclEvalTest(1, [
            ['checkbutton', $this->checkWidget('.chk'), '-text', '{Test}'],
        ]);

        new CheckButton($this->createWindowStub(), 'Test');
    }
}