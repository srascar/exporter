<?php
/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Writer;

/**
 * Class IndexedWriter
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
class IndexedWriter implements IndexedWriterInterface
{
    protected $index = 0;

    protected $writer;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function resetIndex()
    {
        return $this->index = 0;
    }

    public function incrementIndex()
    {
        ++$this->index;
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function open()
    {
        $this->resetIndex();
        $this->writer->open();
    }

    /**
     * @param array $data
     */
    public function write(array $data)
    {
        // Increment indexes before any operation
        // to ensure they are updated before
        // Exceptions are raised
        $this->incrementIndex();
        $this->writer->write($data);
    }

    public function close()
    {
        $this->writer->close();
    }
}
