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

use Exporter\Exception\SkipableException;

/**
 * Class ErrorAllowedWriter
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */

class ErrorAllowedWriter implements ErrorAllowedWriterInterface
{
    private $errors;

    private $writer;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function open()
    {
        $this->writer->open();
    }

    /**
     * @param array $data
     */
    public function write(array $data)
    {
        try {
            $this->writer->write($data);
        } catch (SkipableException $skippedException) {
            $this->errors[] = $data;
        }
    }

    public function close()
    {
        $this->writer->close();
    }
}
