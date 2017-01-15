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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class DoctrineEntityWriter
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
class DoctrineEntityWriter extends DoctrineDBALWriter
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;

    /**
     * @var ClassMetadata
     */
    protected $classMetadata;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var array
     */
    protected $identifiersValues = [];

    /**
     * DefaultDbWriter constructor.
     *
     * @param EntityManagerInterface    $manager
     * @param string                    $className
     * @param string                    $tableName
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(
        EntityManagerInterface $manager,
        $className,
        $tableName = null,
        PropertyAccessorInterface $propertyAccessor = null
    ) {
        $this->manager          = $manager;
        $this->className        = $className;
        $this->classMetadata    = $this->manager->getClassMetadata($this->className);
        $this->tableName        = $tableName ?: $this->classMetadata->getTableName();
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
        parent::__construct($manager->getConnection(), $this->tableName);
    }

    /**
     * Map entity field names with column names defined in Doctrine
     *
     * @param array  $data      The data that need to be persist
     * @param string $tableName The table to insert into
     */
    public function write(array $data, $tableName = null)
    {
        $params = [];
        $types  = [];

        foreach ($this->classMetadata->columnNames as $columnName) {
            $fieldName           = $this->classMetadata->fieldNames[$columnName];

            if (in_array($fieldName, $data)) {
                $types[]             = $this->classMetadata->getTypeOfColumn($columnName);
                $params[$columnName] = $data[$fieldName] ?? null;
            }
        }

        foreach ($this->classMetadata->getAssociationMappings() as $associationMapping) {
            if ($associationMapping['type'] == ClassMetadata::MANY_TO_ONE || $associationMapping['type'] == ClassMetadata::ONE_TO_ONE) {
                $parentMetadata = $this->manager->getClassMetadata($associationMapping['targetEntity']);

                foreach ($parentMetadata->getIdentifierFieldNames() as $index => $parentIdentifier) {
                    if (in_array($parentIdentifier, $data)) {
                        $params[$associationMapping['joinColumns'][$index]['name']] = $data[$parentIdentifier];
                        $types[] = $parentMetadata->getTypeOfColumn($associationMapping['joinColumns'][$index]['name']);
                    }
                }
            }
        }

        parent::write($params, $types);
    }
}
