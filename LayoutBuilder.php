<?php

namespace Oro\Component\Layout;

class LayoutBuilder implements LayoutBuilderInterface
{
    /** @var RawLayoutBuilderInterface */
    protected $rawLayoutBuilder;

    /** @var DeferredRawLayoutManipulatorInterface */
    protected $layoutManipulator;

    /** @var LayoutViewFactoryInterface */
    protected $layoutViewFactory;

    /** @var LayoutFactoryInterface */
    protected $layoutFactory;

    /**
     * @param RawLayoutBuilderInterface             $rawLayoutBuilder
     * @param DeferredRawLayoutManipulatorInterface $layoutManipulator
     * @param LayoutViewFactoryInterface            $layoutViewFactory
     * @param LayoutFactoryInterface                $layoutFactory
     */
    public function __construct(
        RawLayoutBuilderInterface $rawLayoutBuilder,
        DeferredRawLayoutManipulatorInterface $layoutManipulator,
        LayoutViewFactoryInterface $layoutViewFactory,
        LayoutFactoryInterface $layoutFactory
    ) {
        $this->rawLayoutBuilder  = $rawLayoutBuilder;
        $this->layoutManipulator = $layoutManipulator;
        $this->layoutViewFactory = $layoutViewFactory;
        $this->layoutFactory     = $layoutFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function add($id, $parentId = null, $blockType = null, array $options = [])
    {
        $this->layoutManipulator->add($id, $parentId, $blockType, $options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($id)
    {
        $this->layoutManipulator->remove($id);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function move($id, $parentId = null, $siblingId = null, $prepend = false)
    {
        $this->layoutManipulator->move($id, $parentId, $siblingId, $prepend);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAlias($alias, $id)
    {
        $this->layoutManipulator->addAlias($alias, $id);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAlias($alias)
    {
        $this->layoutManipulator->removeAlias($alias);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($id, $optionName, $optionValue)
    {
        $this->layoutManipulator->setOption($id, $optionName, $optionValue);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeOption($id, $optionName)
    {
        $this->layoutManipulator->removeOption($id, $optionName);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLayout(ContextInterface $context, $rootId = null)
    {
        $this->layoutManipulator->applyChanges();
        $rawLayout = $this->rawLayoutBuilder->getRawLayout();
        $rootView  = $this->layoutViewFactory->createView($rawLayout, $context, $rootId);
        $layout    = $this->layoutFactory->createLayout($rootView);

        return $layout;
    }
}
