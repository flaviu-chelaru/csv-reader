<?php

namespace cfv1000\CsvReader\Tests;

use cfv1000\CsvReader\Reader;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    public $root;

    protected function setUp(): void
    {
        $this->root = vfsStream::setup('data', 0, [
            'csv' => [
                'file01.csv' => implode(PHP_EOL, [
                    'A1,B1,C1',
                    'A2,B2,C2',
                    'A3,B3,C3',
                    'A4,B4,C4',
                    'A5,B5,C5',
                ]),
                'file02.csv' => implode(PHP_EOL, [
                    'A1|B1|C1',
                    'A2|B2|C2',
                    'A3|B3|C3',
                    'A4|B4|C4',
                    'A5|B5|C5',
                ])
            ]
        ]);
    }

    /**
     * Configurations on column separators
     */
    public function columnLineSeparatorDataProvider(): array
    {
        return [
            'initial file' => [
                'file' => '/csv/file01.csv',
                'column separator' => ',',
            ],
            'pipe column separator' => [
                'file' => '/csv/file02.csv',
                'column separator' => '|'
            ]
        ];
    }

    /**
     * @dataProvider columnLineSeparatorDataProvider
     */
    public function testReader(string $file, string $columnSeparator): void
    {
        $reader = new Reader($this->root->url() . $file, $columnSeparator);
        $data = $reader->current();
        $this->assertSame(['A1', 'B1', 'C1'], $data);
        $this->assertSame(1, $reader->key());
    }

    /**
     * @dataProvider columnLineSeparatorDataProvider
     */
    public function testKey(string $file, string $columnSeparator): void
    {
        $reader = new Reader($this->root->url() . $file, $columnSeparator);
        $reader->next();
        $reader->next();

        $this->assertSame(3, $reader->key());        
    }

    public function testCount(): void
    {
        $reader = new Reader($this->root->url() . '/csv/file01.csv');
        $this->assertSame(4, $reader->count());
    }

    /**
     * @dataProvider columnLineSeparatorDataProvider
     */
    public function testRewind(string $file, string $columnSeparator): void
    {
        $reader = new Reader($this->root->url() . $file, $columnSeparator);
        $this->assertSame(['A1', 'B1', 'C1'], $reader->current());
        $this->assertSame(['A2', 'B2', 'C2'], $reader->current());
        $reader->rewind();
        $this->assertSame(['A1', 'B1', 'C1'], $reader->current());
    }

    public function testTell(): void
    {
        $reader = new Reader($this->root->url() . '/csv/file01.csv');
        $this->assertSame(0, $reader->tell());
        $reader->current();
        $this->assertSame(10, $reader->tell());
        $reader->current();
        $this->assertSame(20, $reader->tell());
    }

    public function testSeekLines(): void
    {
        $reader = new Reader($this->root->url() . '/csv/file01.csv');
        $reader->seek(30);

        $data = $reader->current();
        $this->assertSame(['A4', 'B4', 'C4'], $data);
    }
}