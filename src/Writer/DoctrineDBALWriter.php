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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Exporter\Exception\SkipableException;

/**
 * Class DoctrineDBALWriter
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
class DoctrineDBALWriter implements WriterInterface
{
    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var string
     */
    protected $tableName;

    protected $isSuccessful = true;

    public function __construct(Connection $conn, $tableName)
    {
        $this->conn = $conn;
        $this->tableName = $tableName;
    }

    public function open()
    {
        $this->conn->beginTransaction();
    }

    /**
     * @param array $data  The data to write
     * @param array $types Types of the inserted data
     *
     * @throws SkipableException
     */
    public function write(array $data, array $types = [])
    {
        try {
            $this->conn->insert($this->tableName, $data, $types);
        } catch (DBALException $e) {
            $this->isSuccessful = false;
            throw new SkipableException("Write Exception", $e->getCode(), $e);
        }
    }

    public function close()
    {
        $this->isSuccessful ? $this->conn->commit() : $this->conn->rollback();
    }
}
