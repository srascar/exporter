<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter;

use Exporter\Source\SourceIteratorInterface;
use Exporter\Writer\CycleManagerInterface;
use Exporter\Writer\WriterInterface;

class CyclicHandler
{
    public static function export(SourceIteratorInterface $source, WriterInterface $writer, CycleManagerInterface $cyclicManager, $itemsPerCycle)
    {
        $index = 0;
        $cycleIndex = 0;

        $cyclicManager->startCycle($source, $writer, $cycleIndex);

        foreach ($source as $data) {
            if ($index !== 0 && !($index%$itemsPerCycle)) {
                ++$cycleIndex;
                $cyclicManager->startCycle($source, $writer, $cycleIndex);
            }

            $writer->write($data);
            ++$index;

            if (!($index%$itemsPerCycle)) {
                $cyclicManager->finishCycle($source, $writer, $cycleIndex);
            }
        }

        $writer->close();
    }
}
