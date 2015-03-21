<?php

namespace Oro\Component\Layout\Tests\Unit\Extension\Theme\Loader;

use Oro\Component\Layout\Extension\Theme\Loader\ResourceIterator;

class ResourceIteratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    protected $resources = [
        'base'  => [
            'default.yml',
            'oro_dashboard_view' => [
                'default2.yml',
                'update.php'
            ],
            'oro_window'         => [
                '3rd_level' => [
                    'default3.yml',
                ]
            ]
        ],
        'black' => [
            'default_black.yml',
            'oro_dashboard_view' => [
                'default_black.php'
            ],
        ]
    ];

    public function testIteratorReturnAllKnownResources()
    {
        $this->assertSame(
            [
                'default.yml',
                'default2.yml',
                'update.php',
                'default3.yml',
                'default_black.yml',
                'default_black.php'
            ],
            $this->getCreatedResources()
        );
    }

    /**
     * @return array
     */
    protected function getCreatedResources()
    {
        $created = [];
        $factory = $this->getMock('Oro\Component\Layout\Extension\Theme\Loader\ResourceFactoryInterface');
        $factory->expects($this->any())->method('create')
            ->willReturnCallback(
                function ($resource) use (&$created) {
                    return $created[] = $resource;
                }
            );

        $iterator = new ResourceIterator($factory, $this->resources);
        iterator_to_array($iterator);

        return $created;
    }
}