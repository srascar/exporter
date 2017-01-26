<?php
/*
 * This file is part of the Carmignac project.
 * 
 * (c) 2016 - Carmignac
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Interfaces;

use Exporter\Source\SourceIteratorInterface;
use Exporter\Writer\WriterInterface;

/**
 * Class RecoverInterface
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
interface RecoverInterface
{
    public function recover(SourceIteratorInterface $source, WriterInterface $writer, \Exception $exception, $data);
}
