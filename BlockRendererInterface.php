<?php

namespace Oro\Component\Layout;

interface BlockRendererInterface
{
    /**
     * Renders the block
     *
     * @param BlockView $view The view for which to render the block
     *
     * @return string
     */
    public function renderBlock(BlockView $view);

    /**
     * Sets the theme(s) to be used for rendering a view and its children
     *
     * @param BlockView       $view   The view to assign the theme(s) to
     * @param string|string[] $themes The theme(s). For example 'MyBundle:Layout:my_theme.html.twig'
     */
    public function setTheme(BlockView $view, $themes);
}