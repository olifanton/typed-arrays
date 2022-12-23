<?php declare(strict_types=1);

use Olifanton\TypedArrays\ArrayBuffer;
use Olifanton\TypedArrays\ArrayBufferView;
use PHPUnit\Framework\TestCase;

class ArrayBufferViewTest extends TestCase
{
    public function testProperty(): void
    {
        $buffer = new ArrayBuffer(16);
        $uselessView = new class($buffer) extends ArrayBufferView
        {
            public function __construct(ArrayBuffer $buf) {
                $this->buffer = $buf;
                $this->byteOffset = 0;
                $this->byteLength = $buf->byteLength;
            }
        };

        $this->assertEquals($buffer, $uselessView->buffer);
        $this->assertEquals($buffer->byteLength, $uselessView->byteLength);
        $this->assertEquals(0, $uselessView->byteOffset);
    }

    public function testMissingProperty(): void
    {
        $uselessView = new class extends ArrayBufferView
        {
        };

        $this->expectException(\Exception::class);
        /** @noinspection PhpUndefinedFieldInspection */
        $uselessView->foobar;
    }
}
