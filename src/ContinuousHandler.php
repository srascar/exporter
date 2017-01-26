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

use Exporter\Exception\SkipableException;
use Exporter\Source\SeekableSourceIteratorInterface;
use Exporter\Writer\ErrorAllowedWriterInterface;

class ContinuousHandler
{
    public static function export(SeekableSourceIteratorInterface $source, ErrorAllowedWriterInterface $writer)
    {
        $writer->open();

        foreach ($source as $data) {
            try {
                $writer->write($data);
            } catch (SkipableException $exception) {
                $writer->recover();
                $source->seek($writer->getLastValidIndex());
            }
        }

        $writer->close();
    }
}
