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
 * interface CyclicInterface
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
interface CyclicWriterInterface extends IndexedWriterInterface
{
    public function getCycleIndex();

    public function startCycle();

    public function finishCycle();

    public function resetCycleIndex();

    public function incrementCycleIndex();

    public function setCycleIndex($cycleIndex);
}
