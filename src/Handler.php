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
use Exporter\Writer\WriterInterface;

class Handler
{
    public static function export(SourceIteratorInterface $source, WriterInterface $writer)
    {
        $writer->open();

        foreach ($source as $data) {
            $writer->write($data);
        }

        $writer->close();
    }
}
