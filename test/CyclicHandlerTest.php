<?php
/*
 * This file is part of the Carmignac project.
 * 
 * (c) 2016 - Carmignac
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Exporter\CyclicHandler;
use Exporter\Source\ArraySourceIterator;

/**
 * Class CyclicHandlerTest
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
class CyclicHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExport()
    {
        $array = [];

        foreach (range(0, 95, 1) as $i) {
            $array[] = [$i];
        }

        $source       = new ArraySourceIterator($array);
        $writer       = $this->getMock('Exporter\Writer\WriterInterface');
        $cycleManager = $this->getMock('Exporter\Interfaces\CycleManagerInterface');

        $cycleManager->expects($this->exactly(10))->method('startCycle');
        $cycleManager->expects($this->exactly(10))->method('finishCycle');

        CyclicHandler::export($source, $writer, $cycleManager, 10);
    }
}
