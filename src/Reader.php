<?php

namespace cfv1000\CsvReader;

use Countable;
use Iterator;
use SplFileObject;
use SplFileInfo;

class Reader implements Iterator, Countable
{
    /**
     * @var SplFileObject
     */
    private $file;

    /**
     * @var string
     */
    private $columnSeparator;

    /**
     * @var string
     */
    private $lineSeparator;

    /**
     * Column enclosure character
     *
     * @var string
     */
    private $enclosure;

    /**
     * Reader constructor.
     *
     * @param string $columnSeparator
     * @param string $enclosure
     * @param string $lineSeparator
     */
    public function __construct(string $file, $columnSeparator = ',', $enclosure = '', $lineSeparator = PHP_EOL)
    {
        $fileInfo = new SplFileInfo($file);
        $this->file = $fileInfo->openFile('rb+');

        $this->columnSeparator = $columnSeparator;
        $this->lineSeparator = $lineSeparator;
        $this->enclosure = $enclosure;

        $this->next();
    }

    public function valid(): bool
    {
        return !$this->file->eof();
    }

    public function next(): void
    {
        $this->file->next();
    }

    /**
     * @return string[]
     */
    public function current(): array
    {
        $buffer = '';
        do {
            $buffer .= $this->file->fread(1);
            $matchLineSeparator = strpos($buffer, $this->lineSeparator);
        } while ($this->valid() && !$matchLineSeparator);

        $line = explode($this->columnSeparator, $buffer);

        return array_map(function ($item): string {
            return trim($item, " \t\n\r\0\x0B\xEF\xBB\xBF" . $this->enclosure);
        }, $line);
    }

    public function key(): int
    {
        return $this->file->key();
    }

    public function rewind(): void
    {
        $this->file->rewind();
    }

    /**
     * Jump to $offset file pointer
     */
    public function seek(int $offset): void
    {
        $this->file->fseek($offset);
    }

    /**
     * Return current file pointer
     */
    public function tell(): int
    {
        return $this->file->ftell();
    }

    /**
     * Will return number of lines in the file
     * File cursor will be set to the end of file for it (rewind is required if you want to loop through it)
     */
    public function count(): int
    {
        $this->file->seek(PHP_INT_MAX);
        return $this->file->key();
    }
}
