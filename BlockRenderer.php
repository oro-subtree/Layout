<?php

namespace Oro\Component\Layout;

use Symfony\Component\Form\FormRendererInterface;

class BlockRenderer implements BlockRendererInterface
{
    /** @var FormRendererInterface */
    protected $innerRenderer;

    /**
     * @param FormRendererInterface $innerRenderer
     */
    public function __construct(FormRendererInterface $innerRenderer)
    {
        $this->innerRenderer = $innerRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function renderBlock(BlockView $view)
    {
        return $this->innerRenderer->searchAndRenderBlock($view, 'widget');
    }

    /**
     * {@inheritdoc}
     */
    public function setTheme(BlockView $view, $themes)
    {
        $this->innerRenderer->setTheme($view, $themes);
    }
}