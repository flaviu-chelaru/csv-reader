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
     * @var int
     */
    private $key = 0;

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
     * @param string $file
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

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return !$this->file->eof();
    }

    public function next()
    {
        ++$this->key;
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

        return array_map(function ($item) {
            return trim($item, " \t\n\r\0\x0B\xEF\xBB\xBF" . $this->enclosure);
        }, $line);
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->key;
    }

    public function rewind()
    {
        $this->key = 0;
    }

    /**
     * Jump to $offset file pointer
     *
     * @param int $offset
     */
    public function seek(int $offset)
    {
        $this->file->fseek($offset);
    }

    /**
     * Return current file pointer
     *
     * @return int
     */
    public function tell(): int
    {
        return $this->file->ftell();
    }

    /**
     * Will return number of lines in the file
     * File cursor will be set to the end of file for it (rewind is required if you want to loop through it)
     * @return int
     */
    public function count()
    {
        $this->file->seek(PHP_INT_MAX);
        return $this->file->key();
    }
}
