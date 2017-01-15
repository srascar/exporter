<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Source;

/**
 * Read data from a csv file.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class CsvSourceIterator implements SeekableSourceIteratorInterface
{
    /**
     * @var string
     */
    protected $filename = null;

    /**
     * @var \SplFileObject
     */
    protected $file = null;

    /**
     * @var string|null
     */
    protected $delimiter = null;

    /**
     * @var string|null
     */
    protected $enclosure = null;

    /**
     * @var string|null
     */
    protected $escape = null;

    /**
     * @var bool|null
     */
    protected $hasHeaders = null;

    /**
     * @var array
     */
    protected $lines = array();

    /**
     * @var array
     */
    protected $columns = array();

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var array
     */
    protected $currentLine = array();

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @param bool   $hasHeaders
     */
    public function __construct($filename, $delimiter = ',', $enclosure = '"', $escape = '\\', $hasHeaders = true)
    {
        $this->filename = $filename;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->hasHeaders = $hasHeaders;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->currentLine;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->initializeRead();
        $line = $this->file->fgetcsv();

        if (false === $line) {
            throw new \RuntimeException(sprintf('An error occurred while reading the csv %s.', $this->file->getRealPath()));
        }

        $this->currentLine = $line;
        $this->position = $this->file->key();

        if ($this->hasHeaders) {
            if ($line === array(null) || $line === null) {
                $this->currentLine =  null;

                return;
            }

            if (count($this->columns) !== count($line)) {
                $this->invalidColumnCount($line);
            }

            $this->currentLine = array_combine($this->columns, $line);;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->initializeRead();

        if ($this->position !== 1) {
            $this->seek(1);
        }

        $this->next();
    }

    /**
     * {@inheritdoc}
     */
    public function seek($position)
    {
        if ($this->file instanceof \SplFileObject) {
            $this->file->seek($position);
            $this->position = $this->file->key();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        if (!is_array($this->currentLine)) {
            return false;
        }

        return true;
    }

    /**
     * Initialize read process setting CSV options
     * and settings field names. (Assuming that first line is column name)
     */
    protected function initializeRead()
    {
        if (!$this->file) {
            $this->file = new \SplFileObject($this->filename);
            $this->file->setFlags(
                \SplFileObject::READ_CSV |
                \SplFileObject::READ_AHEAD |
                \SplFileObject::SKIP_EMPTY |
                \SplFileObject::DROP_NEW_LINE
            );
            $this->file->setCsvControl($this->delimiter, $this->enclosure, $this->escape);

            if ($this->hasHeaders) {
                $this->columns = $this->file->fgetcsv();
                $this->position = $this->file->key();
            }
        }
    }
}
