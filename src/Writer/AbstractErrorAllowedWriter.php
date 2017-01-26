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

abstract class AbstractErrorAllowedWriter extends IndexedWriter implements ErrorAllowedWriterInterface
{
    protected $errors;

    protected $writer;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    abstract public function getLastValidIndex();

    abstract public function recover();

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
     *
     * @throws SkipableException
     */
    public function write(array $data)
    {
        try {
            parent::write($data);
        } catch (\Exception $exception) {
            $this->errors[] = [
                'data'      => $data,
                'exception' => $exception,
            ];

            throw new SkipableException("Write Exception", $exception->getCode(), $exception);
        }
    }

    public function close()
    {
        $this->writer->close();
    }
}
