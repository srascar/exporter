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

use Exporter\Exception\SkippableException;
use Exporter\Interfaces\CycleManagerInterface;
use Exporter\Interfaces\RecoverInterface;
use Exporter\Source\SourceIteratorInterface;
use Exporter\Writer\WriterInterface;

class ContinuousCyclicHandler
{
    /**
     * @param SourceIteratorInterface $source
     * @param WriterInterface         $writer
     * @param RecoverInterface        $recoverService
     * @param CycleManagerInterface   $cyclicManager
     * @param int                     $itemsPerCycle
     */
    public static function export(
        SourceIteratorInterface $source,
        WriterInterface $writer,
        RecoverInterface $recoverService,
        CycleManagerInterface $cyclicManager,
        $itemsPerCycle
    ) {
        $index = 0;
        $cycleIndex = 0;

        $cyclicManager->startCycle($source, $writer, $cycleIndex);

        foreach ($source as $data) {
            if ($index !== 0 && !($index%$itemsPerCycle)) {
                ++$cycleIndex;
                $cyclicManager->startCycle($source, $writer, $cycleIndex);
            }

            try {
                $writer->write($data);
            } catch (SkippableException $exception) {
                $recoverService->recover($source, $writer, $exception, $data, $index, $cycleIndex);
            }

            ++$index;

            if (!($index%$itemsPerCycle)) {
                $cyclicManager->finishCycle($source, $writer, $cycleIndex);
            }
        }

        if ($index%$itemsPerCycle) {
            $cyclicManager->finishCycle($source, $writer, $cycleIndex);
        }
    }
}
