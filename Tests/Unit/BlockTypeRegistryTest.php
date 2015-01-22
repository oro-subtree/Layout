<?php

namespace Oro\Component\Layout\Tests\Unit;

use Oro\Component\Layout\BlockTypeRegistry;
use Oro\Component\Layout\Exception\InvalidArgumentException;

class BlockTypeRegistryTest extends \PHPUnit_Framework_TestCase
{
    /** @var BlockTypeRegistry */
    protected $registry;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $blockTypeFactory;

    protected function setUp()
    {
        $this->blockTypeFactory = $this->getMock('Oro\Component\Layout\BlockTypeFactoryInterface');
        $this->registry    = new BlockTypeRegistry($this->blockTypeFactory);
    }

    public function testGetBlockType()
    {
        $widgetBlockType = $this->getMock('Oro\Component\Layout\BlockTypeInterface');

        $this->blockTypeFactory->expects($this->once())
            ->method('createBlockType')
            ->with('widget')
            ->will($this->returnValue($widgetBlockType));
        $widgetBlockType->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('widget'));

        $this->assertSame($widgetBlockType, $this->registry->getBlockType('widget'));
        // check that the created block type is cached
        $this->assertSame($widgetBlockType, $this->registry->getBlockType('widget'));
    }

    public function testHasBlockType()
    {
        $widgetBlockType = $this->getMock('Oro\Component\Layout\BlockTypeInterface');
        $widgetBlockType->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('widget'));
        $buttonBlockType = $this->getMock('Oro\Component\Layout\BlockTypeInterface');
        $buttonBlockType->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('button'));

        $this->blockTypeFactory->expects($this->exactly(2))
            ->method('createBlockType')
            ->will(
                $this->returnValueMap(
                    [
                        ['widget', $widgetBlockType],
                        ['button', $buttonBlockType]
                    ]
                )
            );

        $this->assertTrue($this->registry->hasBlockType('widget'));
        $this->assertTrue($this->registry->hasBlockType('button'));
        // check that the created block type is cached
        $this->assertTrue($this->registry->hasBlockType('button'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetTypeWithWrongArgument()
    {
        $this->registry->getBlockType(1);
    }

    public function testHasTypeWithWrongArgument()
    {
        $this->assertFalse($this->registry->hasBlockType(1));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetTypeWithWrongTypeName()
    {
        $widgetBlockType = $this->getMock('Oro\Component\Layout\BlockTypeInterface');

        $this->blockTypeFactory->expects($this->once())
            ->method('createBlockType')
            ->with('widget')
            ->will($this->returnValue($widgetBlockType));
        $widgetBlockType->expects($this->exactly(2))
            ->method('getName')
            ->will($this->returnValue('button'));

        $this->registry->getBlockType('widget');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetTypeUndefined()
    {
        $this->blockTypeFactory->expects($this->once())
            ->method('createBlockType')
            ->with('widget')
            ->will($this->returnValue(null));

        $this->registry->getBlockType('widget');
    }
}