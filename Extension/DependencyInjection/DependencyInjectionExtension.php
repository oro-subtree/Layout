<?php

namespace Oro\Component\Layout\Extension\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Component\Layout\Exception;
use Oro\Component\Layout\ExtensionInterface;

class DependencyInjectionExtension implements ExtensionInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * The block type services registered in DI container
     *
     * @var string[]
     *
     * Example:
     *  [
     *      'block_type_1' => 'service1',
     *      'block_type_2' => 'service2'
     *  ]
     */
    private $typeServiceIds;

    /**
     * The block type extension services registered in DI container
     *
     * @var array of string[]
     *
     * Example:
     *  [
     *      'block_type_1' => array of strings,
     *      'block_type_2' => array of strings
     *  ]
     */
    private $typeExtensionServiceIds;

    /**
     * The layout update services registered in DI container
     *
     * @var array of string[]
     *
     * Example:
     *  [
     *      'item_1' => array of strings,
     *      'item_2' => array of strings
     *  ]
     */
    private $layoutUpdateServiceIds;

    /**
     * @param ContainerInterface $container
     * @param array              $blockTypeServiceIds          string[]
     * @param array              $blockTypeExtensionServiceIds array of string[]
     * @param array              $layoutUpdateServiceIds       array of string[]
     */
    public function __construct(
        ContainerInterface $container,
        array $blockTypeServiceIds,
        array $blockTypeExtensionServiceIds,
        array $layoutUpdateServiceIds
    ) {
        $this->container               = $container;
        $this->typeServiceIds          = $blockTypeServiceIds;
        $this->typeExtensionServiceIds = $blockTypeExtensionServiceIds;
        $this->layoutUpdateServiceIds  = $layoutUpdateServiceIds;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockType($name)
    {
        if (!isset($this->typeServiceIds[$name])) {
            throw new Exception\InvalidArgumentException(
                sprintf('The block type "%s" is not registered with the service container.', $name)
            );
        }

        $type = $this->container->get($this->typeServiceIds[$name]);

        if ($type->getName() !== $name) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'The type name specified for the service "%s" does not match the actual name. '
                    . 'Expected "%s", given "%s".',
                    $this->typeServiceIds[$name],
                    $name,
                    $type->getName()
                )
            );
        }

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function hasBlockType($name)
    {
        return isset($this->typeServiceIds[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockTypeExtensions($name)
    {
        $extensions = array();

        if (isset($this->typeExtensionServiceIds[$name])) {
            foreach ($this->typeExtensionServiceIds[$name] as $serviceId) {
                $extensions[] = $this->container->get($serviceId);
            }
        }

        return $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function hasBlockTypeExtensions($name)
    {
        return isset($this->typeExtensionServiceIds[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutUpdates($id)
    {
        $layoutUpdates = array();

        if (isset($this->layoutUpdateServiceIds[$id])) {
            foreach ($this->layoutUpdateServiceIds[$id] as $serviceId) {
                $layoutUpdates[] = $this->container->get($serviceId);
            }
        }

        return $layoutUpdates;
    }

    /**
     * {@inheritdoc}
     */
    public function hasLayoutUpdates($id)
    {
        return isset($this->layoutUpdateServiceIds[$id]);
    }
}