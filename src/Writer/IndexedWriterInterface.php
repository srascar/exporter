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

/**
 * interface IndexedInterface
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
interface IndexedWriterInterface extends WriterInterface
{
    public function getIndex();

    public function resetIndex();

    public function incrementIndex();

    public function setIndex($index);
}
