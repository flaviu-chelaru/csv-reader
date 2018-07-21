# csv-reader
A CSV reader based on iterators. Offers better support for line endings

## Features
- custom line endings
- ability to pause / resume reading

## usage

```php

use cfv1000\CsvReader\Reader;

$csv = new Reader($filename);
// $csv->seek(); jump to specified pointer. Suggestion: only use values returned by $csv->tell(). Will cause weird results otherwise. (if reading from the middle of the line, for example) 
foreach($csv as $csvLine)
{
 // print $csv->tell(); -> returns current position in the file
 print_r($csvLine);
} 
```
