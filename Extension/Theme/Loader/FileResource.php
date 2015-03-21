<?php

namespace Oro\Component\Layout\Extension\Theme\Loader;

class FileResource
{
    /** @var string */
    protected $filename;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFilename();
    }
}