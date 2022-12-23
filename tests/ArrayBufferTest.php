<?php declare(strict_types=1);

use Olifanton\TypedArrays\ArrayBuffer;
use Olifanton\TypedArrays\Int8Array;
use PHPUnit\Framework\TestCase;

class ArrayBufferTest extends TestCase
{
    public function testInstantiation(): void
    {
        $emptyBuf = new ArrayBuffer(0);
        $this->assertEquals(0, $emptyBuf->byteLength);

        $twelveBuf = new ArrayBuffer(12);
        $this->assertEquals(12, $twelveBuf->byteLength);
    }

    public function testSlicing(): void
    {
        $twelveBuf = new ArrayBuffer(12);

        // slices from 4 to end, so 8 bytes
        $fourSlice = $twelveBuf->slice(4);
        $this->assertEquals(8, $fourSlice->byteLength);

        // slices from 4 to 8, so 4 bytes
        $fourFourSlice = $twelveBuf->slice(4, 8);
        $this->assertEquals(4, $fourFourSlice->byteLength);

        // negative indices should be interpreted as index from end
        $minusOneSlice = $twelveBuf->slice(-1);
        $this->assertEquals(1, $minusOneSlice->byteLength);

        $minusFourTenSlice = $twelveBuf->slice(-4, 10);
        $this->assertEquals(2, $minusFourTenSlice->byteLength);
    }

    public function testIsView(): void
    {
        $this->assertFalse(ArrayBuffer::isView(new \StdClass));
        $this->assertTrue(ArrayBuffer::isView(new Int8Array(1)));
    }

    public function testMissingProperty(): void
    {
        $buf = new ArrayBuffer(0);
        $this->expectException(\Exception::class);
        /** @noinspection PhpUndefinedFieldInspection */
        $buf->foobar;
    }
}
