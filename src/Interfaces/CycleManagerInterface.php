<?php
/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Interfaces;

use Exporter\Source\SourceIteratorInterface;
use Exporter\Writer\WriterInterface;

/**
 * interface CycleManagerInterface
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
interface CycleManagerInterface
{
    /**
     * @param SourceIteratorInterface $source
     * @param WriterInterface         $writer
     * @param int                     $cycleIndex
     */
    public function startCycle(SourceIteratorInterface $source, WriterInterface $writer, $cycleIndex);

    /**
     * @param SourceIteratorInterface $source
     * @param WriterInterface         $writer
     * @param int                     $cycleIndex
     */
    public function finishCycle(SourceIteratorInterface $source, WriterInterface $writer, $cycleIndex);
}
