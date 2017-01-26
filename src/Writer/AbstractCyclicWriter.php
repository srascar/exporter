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
 * Class CyclicWriter
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
abstract class AbstractCyclicWriter implements CyclicWriterInterface
{
    protected $cycleIndex = 0;

    protected $writer;

    protected $itemsPerCycle;

    protected $isCyclic;

    public function __construct(IndexedWriterInterface $writer, $itemsPerCycle = 0)
    {
        $this->writer        = $writer;
        $this->itemsPerCycle = $itemsPerCycle;
        $this->isCyclic = $itemsPerCycle !== 0;
    }

    abstract public function startCycle();

    abstract public function finishCycle();

    public function open()
    {
        $this->resetCycleIndex();

        if ($this->isCyclic) {
            $this->startCycle();
        }

        $this->writer->open();
    }

    /**
     * @param array $data
     */
    public function write(array $data)
    {
        if ($this->isCyclic && $this->writer->getIndex() !== 0 && !$this->writer->getIndex()%$this->itemsPerCycle) {
            $this->incrementCycleIndex();
        }

        $this->writer->write($data);

        if ($this->isCyclic && !$this->writer->getIndex()%$this->itemsPerCycle) {
            $this->finishCycle();
        }
    }

    public function close()
    {
        $this->finishCycle();
        $this->writer->close();
    }

    public function getCycleIndex()
    {
        return $this->cycleIndex;
    }

    public function incrementCycleIndex()
    {
        ++$this->cycleIndex;
    }

    public function setCycleIndex($cycleIndex)
    {
        $this->cycleIndex = $cycleIndex;
    }

    public function resetCycleIndex()
    {
        return $this->cycleIndex = 0;
    }


    public function getIndex()
    {
        $this->writer->getIndex();
    }

    public function incrementIndex()
    {
        $this->writer->incrementIndex();
    }

    public function setIndex($index)
    {
        $this->writer->setIndex($index);
    }

    public function resetIndex()
    {
        $this->writer->resetIndex();
    }

    public function getItemsPerCycle()
    {
        return $this->itemsPerCycle;
    }
}
