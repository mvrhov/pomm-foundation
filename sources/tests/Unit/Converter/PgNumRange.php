<?php
/*
 * This file is part of PommProject's Foundation package.
 *
 * (c) 2014 Grégoire HUBERT <hubert.greg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PommProject\Foundation\Test\Unit\Converter;

use PommProject\Foundation\Test\Unit\Converter\BaseConverter;
use PommProject\Foundation\Converter\Type\NumRange;

class PgNumRange extends BaseConverter
{
    public function testFromPg()
    {
        $session = $this->buildSession();
        $this
            ->object($this->newTestedInstance()->fromPg('[1,3)', 'int4range', $session))
            ->isInstanceOf('PommProject\Foundation\Converter\Type\NumRange')
            ->variable($this->newTestedInstance()->fromPg(null, 'point', $session))
            ->isNull()
            ;
        $range = $this->newTestedInstance()->fromPg('[1,3)', 'int4range', $session);
        $this
            ->integer($range->start_limit)
            ->isEqualTo(1)
            ->integer($range->end_limit)
            ->isEqualTo(3)
            ->boolean($range->start_incl)
            ->isTrue()
            ->boolean($range->end_incl)
            ->isFalse()
            ;
        $range = $this->newTestedInstance()->fromPg('(-3.1415, -1.6180]', 'numrange', $session);
        $this
            ->float($range->start_limit)
            ->isEqualTo(-3.1415)
            ->float($range->end_limit)
            ->isEqualTo(-1.618)
            ->boolean($range->start_incl)
            ->isFalse()
            ->boolean($range->end_incl)
            ->isTrue()
            ;
    }

    public function testToPg()
    {
        $session = $this->buildSession();
        $this
            ->string($this->newTestedInstance()->toPg(new NumRange('[1,3)'), 'myrange', $session))
            ->isEqualTo("myrange('[1,3)')")
            ->string($this->newTestedInstance()->toPg(null, 'myrange', $session))
            ->isEqualTo('NULL::myrange')
            ;
    }

    public function testToPgStandardFormat()
    {
        $session = $this->buildSession();
        $this
            ->string($this->newTestedInstance()->toPgStandardFormat(new NumRange('[1,3)'), 'myrange', $session))
            ->isEqualTo("[1,3)")
            ->variable($this->newTestedInstance()->toPgStandardFormat(null, 'myrange', $session))
            ->isNull()
            ;
    }
}
