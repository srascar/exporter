<?php
/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Exporter\ContinuousHandler;
use Exporter\Exception\SkippableException;
use Exporter\Source\ArraySourceIterator;

/**
 * Class ContinuousHandlerTest
 *
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
class ContinuousHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExport()
    {
        $array = [1 => []];

        $source  = new ArraySourceIterator($array);
        $writer  = $this->getMock('Exporter\Writer\WriterInterface');
        $recover = $this->getMock('Exporter\Interfaces\RecoverInterface');

        $writer->expects($this->once())->method('write')->willThrowException(new SkippableException());
        $recover->expects($this->once())->method('recover');

        ContinuousHandler::export($source, $writer, $recover);
    }
}
