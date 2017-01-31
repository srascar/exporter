<?php
/*
 * This file is part of the Carmignac project.
 * 
 * (c) 2016 - Carmignac
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Exporter\ContinuousCyclicHandler;
use Exporter\Exception\SkippableException;
use Exporter\Source\ArraySourceIterator;

/**
 * Class ContinuousCyclicHandlerTest
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
class ContinuousCyclicHandlerTest extends \PHPUnit_Framework_TestCase
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
        $recover      = $this->getMock('Exporter\Interfaces\RecoverInterface');
        $exception    = new SkippableException();

        // Exception raised on the 11th element during the second cycle
        $writer->expects($this->at(10))->method('write')->willThrowException($exception);

        $cycleManager->expects($this->exactly(10))->method('startCycle');
        $cycleManager->expects($this->exactly(10))->method('finishCycle');

        $recover->expects($this->once())->method('recover')->with(
            $source,
            $writer,
            $exception,
            [10],
            10,
            1
        );

        ContinuousCyclicHandler::export($source, $writer, $recover, $cycleManager, 10);
    }
}
