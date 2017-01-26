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
use Exporter\Interfaces\RecoverInterface;
use Exporter\Source\SourceIteratorInterface;
use Exporter\Writer\WriterInterface;

class ContinuousHandler
{
    public static function export(SourceIteratorInterface $source, WriterInterface $writer, RecoverInterface $recoverService)
    {
        $writer->open();

        foreach ($source as $data) {
            try {
                $writer->write($data);
            } catch (SkippableException $exception) {
                $recoverService->recover($source, $writer, $exception, $data);
            }
        }

        $writer->close();
    }
}
