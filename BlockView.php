<?php

namespace Oro\Component\Layout;

use Symfony\Component\Form\FormView;

use Oro\Component\Layout\Block\Type\Options;

/**
 * @method BlockView getParent()
 * @property BlockView[] children
 * @property Options vars
 */
class BlockView extends FormView
{
    /**
     * All layout block views.
     *
     * @var BlockView[]
     */
    public $blocks = [];

    /**
     * @param BlockView $parent
     */
    public function __construct(BlockView $parent = null)
    {
        parent::__construct($parent);

        unset($this->vars['value']);
        $this->vars = new Options($this->vars);
    }
}
