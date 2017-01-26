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

use Exporter\Exception\SkippableException;

/**
 * Class SkippableErrorWriter
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
class SkippableErrorWriter implements WriterInterface
{
    protected $writer;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    public function open()
    {
        $this->writer->open();
    }

    /**
     * @param array $data
     *
     * @throws SkippableException
     */
    public function write(array $data)
    {
        try {
            $this->writer->write($data);
        } catch (\Exception $exception) {
            throw new SkippableException("Write Exception", $exception->getCode(), $exception);
        }
    }

    public function close()
    {
        $this->writer->close();
    }
}
