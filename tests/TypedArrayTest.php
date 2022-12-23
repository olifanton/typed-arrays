<?php declare(strict_types=1);

use Olifanton\TypedArrays\ArrayBuffer;
use Olifanton\TypedArrays\Float32Array;
use Olifanton\TypedArrays\Float64Array;
use Olifanton\TypedArrays\Int16Array;
use Olifanton\TypedArrays\Int32Array;
use Olifanton\TypedArrays\Int8Array;
use Olifanton\TypedArrays\Uint16Array;
use Olifanton\TypedArrays\Uint32Array;
use Olifanton\TypedArrays\Uint8Array;
use PHPUnit\Framework\TestCase;

class TypedArrayTest extends TestCase
{
    public function testInstantiation(): void
    {
        $emptyArr = new Uint8Array(0);
        $this->assertEquals(0, $emptyArr->length);

        $twelveArr = new Uint8Array(12);
        $this->assertEquals(12, $twelveArr->length);

        $arrayArr = new Uint8Array([1, 2, 3, 4]);
        $this->assertEquals(4, $arrayArr->length);
        $this->assertEquals(1, $arrayArr[0]);
        $this->assertEquals(2, $arrayArr[1]);
        $this->assertEquals(3, $arrayArr[2]);
        $this->assertEquals(4, $arrayArr[3]);

        $arrayArrArr = new Uint8Array($arrayArr);
        $this->assertEquals(4, $arrayArr->length);
        $this->assertEquals(1, $arrayArr[0]);
        $this->assertEquals(2, $arrayArr[1]);
        $this->assertEquals(3, $arrayArr[2]);
        $this->assertEquals(4, $arrayArr[3]);

        $emptyBuf = new ArrayBuffer(0);
        $emptyBufArr = new Uint8Array($emptyBuf);
        $this->assertEquals(0, $emptyBufArr->length);

        $twelveBuf = new ArrayBuffer(12);
        $twelveBufFourArr = new Uint8Array($twelveBuf, 4);
        $this->assertEquals(8, $twelveBufFourArr->length);

        $twelveBufFourFourArr = new Uint8Array($twelveBuf, 4, 4);
        $this->assertEquals(4, $twelveBufFourFourArr->length);
    }

    public function testInitOffsetExceeded(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $this->expectException(OutOfBoundsException::class);
        $oneBufArr = new Uint8Array($oneBuf, 1);
    }

    public function testInitLengthExceeded(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $this->expectException(OutOfBoundsException::class);
        $oneBufArr = new Uint8Array($oneBuf, 0, 2);
    }

    public function testInitOffsetAndLengthExceeded(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $this->expectException(OutOfBoundsException::class);
        $oneBufArr = new Uint8Array($oneBuf, 1, 1);
    }

    public function testInitOffsetNonAlignment(): void
    {
        // Uint16Array has alement size of 2, so expects offset multiple of 2
        $threeBuf = new ArrayBuffer(3);
        $this->expectException(InvalidArgumentException::class);
        $threeBufArr = new Uint16Array($threeBuf, 1);
    }

    public function testInitLengthNonAlignment(): void
    {
        // Uint16Array has alement size of 2, so expects length multiple of 2
        $threeBuf = new ArrayBuffer(3);
        $this->expectException(InvalidArgumentException::class);
        $threeBufArr = new Uint16Array($threeBuf);
    }

    public function testRead0xff(): void
    {
        $buf = new ArrayBuffer(4);
        $uint8Arr = new Uint8Array($buf);
        $int8Arr = new Int8Array($buf);
        $uint16Arr = new Uint16Array($buf);
        $int16Arr = new Int16Array($buf);
        $uint32Arr = new Uint32Array($buf);
        $int32Arr = new Int32Array($buf);

        $uint8Arr[0] = 0xff;
        $uint8Arr[1] = 0xff;
        $uint8Arr[2] = 0xff;
        $uint8Arr[3] = 0xff;

        $this->assertEquals(0xff, $uint8Arr[0]);
        $this->assertEquals(-1, $int8Arr[0]);
        $this->assertEquals(0xffff, $uint16Arr[0]);
        $this->assertEquals(-1, $int16Arr[0]);
        $this->assertEquals(0xffffffff, $uint32Arr[0]);
        $this->assertEquals(-1, $int32Arr[0]);
    }

    public function testSet0xff(): void
    {
        $fourBuf = new ArrayBuffer(4);
        $uint32FourBufArr = new Uint32Array($fourBuf);
        $uint8FourBufArr = new Uint8Array($fourBuf);

        $uint32FourBufArr[0] = 0xffffffff;

        $this->assertEquals(0xff, $uint8FourBufArr[0]);
        $this->assertEquals(0xff, $uint8FourBufArr[1]);
        $this->assertEquals(0xff, $uint8FourBufArr[2]);
        $this->assertEquals(0xff, $uint8FourBufArr[3]);

        $fourBuf = new ArrayBuffer(4);
        $int32FourBufArr = new Int32Array($fourBuf);
        $uint8FourBufArr = new Uint8Array($fourBuf);

        $int32FourBufArr[0] = -1;

        $this->assertEquals(0xff, $uint8FourBufArr[0]);
        $this->assertEquals(0xff, $uint8FourBufArr[1]);
        $this->assertEquals(0xff, $uint8FourBufArr[2]);
        $this->assertEquals(0xff, $uint8FourBufArr[3]);

        $twoBuf = new ArrayBuffer(2);
        $uint16TwoBufArr = new Uint16Array($twoBuf);
        $uint8TwoBufArr = new Uint8Array($twoBuf);

        $uint16TwoBufArr[0] = 0xffff;

        $this->assertEquals(0xff, $uint8TwoBufArr[0]);
        $this->assertEquals(0xff, $uint8TwoBufArr[1]);

        $twoBuf = new ArrayBuffer(2);
        $int16TwoBufArr = new Int16Array($twoBuf);
        $uint8TwoBufArr = new Uint8Array($twoBuf);

        $int16TwoBufArr[0] = -1;

        $this->assertEquals(0xff, $uint8TwoBufArr[0]);
        $this->assertEquals(0xff, $uint8TwoBufArr[1]);

        $oneBuf = new ArrayBuffer(1);
        $uint8OneBufArr = new Uint8Array($oneBuf);

        $uint8OneBufArr[0] = 255;

        $this->assertEquals(0xff, $uint8OneBufArr[0]);

        $oneBuf = new ArrayBuffer(1);
        $int8OneBufArr = new Int8Array($oneBuf);
        $uint8OneBufArr = new Uint8Array($oneBuf);

        $int8OneBufArr[0] = -1;

        $this->assertEquals(0xff, $uint8OneBufArr[0]);
    }

    public function testSetAll256(): void
    {
        foreach ([Int8Array::class, Int16Array::class, Int32Array::class, Float32Array::class, Float64Array::class] as $class) {
            $arr = new $class(256);
            $pos = 0;

            for ($i = -128; $i < 128; $i++) {
                $arr[$pos] = $i;
                $pos++;
            }

            $pos = 0;

            for ($i = -128; $i < 128; $i++) {
                $this->assertEquals($arr[$pos], $i);
                $pos++;
            }
        }

        foreach ([Uint8Array::class, Uint16Array::class, Uint32Array::class] as $class) {
            $arr = new $class(256);
            $pos = 0;

            for ($i = 0; $i < 256; $i++) {
                $arr[$pos] = $i;
                $pos++;
            }

            $pos = 0;

            for ($i = 0; $i < 256; $i++) {
                $this->assertEquals($arr[$pos], $i);
                $pos++;
            }
        }
    }

    public function testGetOffsetLessThanZero(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->expectException(OutOfBoundsException::class);
        $foo = $oneBufArr[-1];
    }

    public function testGetOffsetGreaterThanLength(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->expectException(OutOfBoundsException::class);
        $foo = $oneBufArr[1];
    }

    public function testSetOffsetLessThanZero(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->expectException(OutOfBoundsException::class);
        $oneBufArr[-1] = 12;
    }

    public function testSetOffsetGreaterThanLength(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->expectException(OutOfBoundsException::class);
        $oneBufArr[1] = 12;
    }

    public function testUnset(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->expectException(DomainException::class);
        unset($oneBufArr[1]);
    }

    public function testIsset(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->assertFalse(isset($oneBufArr[-1]));
        $this->assertTrue(isset($oneBufArr[0]));
        $this->assertFalse(isset($oneBufArr[1]));
    }

    public function testIssetNonInteger(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->expectException(InvalidArgumentException::class);
        $foo = isset($oneBufArr["test"]);
    }

    public function testGetNonInteger(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->expectException(InvalidArgumentException::class);
        $foo = $oneBufArr["test"];
    }

    public function testSetNonInteger(): void
    {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->expectException(InvalidArgumentException::class);
        $oneBufArr["test"] = 12;
    }

    // TODO: test set/subarray/properties and actual data storage

    public function testMissingProperty(): void
    {
        $buf = new ArrayBuffer(0);
        $this->expectException(Exception::class);
        /** @noinspection PhpUndefinedFieldInspection */
        $buf->foobar;
    }
}
