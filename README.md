[![Tests](https://github.com/flaviu-chelaru/csv-reader/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/flaviu-chelaru/csv-reader/actions/workflows/tests.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/cfv1000/csv-reader.svg?style=flat-square&label=stable)](https://packagist.org/packages/cfv1000/csv-reader)
[![PHP Version Require](https://img.shields.io/packagist/dependency-v/cfv1000/csv-reader/php?style=flat-square)](https://packagist.org/packages/cfv1000/csv-reader)
[![Total Downloads](https://img.shields.io/packagist/dt/cfv1000/csv-reader.svg?style=flat-square)](https://packagist.org/packages/cfv1000/csv-reader)
[![Monthly Downloads](https://img.shields.io/packagist/dm/cfv1000/csv-reader.svg?style=flat-square)](https://packagist.org/packages/cfv1000/csv-reader)
[![License](https://img.shields.io/packagist/l/cfv1000/csv-reader.svg?style=flat-square)](https://github.com/flaviu-chelaru/csv-reader/blob/master/LICENSE)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-9.5-blue.svg?style=flat-square&logo=php)](https://phpunit.de/)
[![Rector](https://img.shields.io/badge/Rector-1.2-blueviolet.svg?style=flat-square)](https://getrector.com/)
[![PSR-4](https://img.shields.io/badge/PSR--4-autoload-brightgreen.svg?style=flat-square)](https://www.php-fig.org/psr/psr-4/)

# 🚀 Why Use This Class?
This class is a solid choice for several reasons:

**Memory Efficiency:** Unlike methods that read the entire file into an array, this class implements the Iterator interface. This means it reads the file line by line, which is crucial for processing large datasets without exhausting your server's memory. This is particularly useful in environments with limited RAM or when dealing with multi-gigabyte files.

**Flexibility:** It allows you to customize the core parameters of your CSV file. You can specify different column, line, and enclosure characters in the constructor. This adaptability makes it suitable for reading CSV files from various sources that may use different delimiters.

**Countable and Iterator Interfaces:** By implementing the Countable interface, you can easily get the total number of lines in the file using the count() method. The Iterator interface implementation means you can use the class directly in foreach loops, providing a clean and familiar way to process each line of the CSV.

**Low-Level Control:** The class gives you fine-grained control over the file pointer with methods like seek() and tell(), which can be useful for advanced operations like resuming a process or navigating to a specific part of the file.

**SplFileObject Utilization:** The class leverages PHP's built-in SplFileObject which is part of the Standard PHP Library (SPL). SplFileObject is an object-oriented interface for file handling, offering a more robust and feature-rich way to interact with files than traditional functions like fopen() and fgetcsv(). This ensures stable and reliable file operations.

# ✍️ Usage Examples
**Basic Usage**
To read a basic comma-separated file, just provide the file path:

```php

use cfv1000\CsvReader\Reader;

$csv = new Reader('path/to/your/file.csv');

foreach ($csv as $line) {
    print_r($line);
}
```

## Customizing Delimiters
If your file uses a different delimiter, like a semicolon, you can specify it:

```php

use cfv1000\CsvReader\Reader;

// Semicolon-separated file
$csv = new Reader('path/to/your/semicolon_file.csv', ';');

foreach ($csv as $line) {
    print_r($line);
}
```

## Counting Lines
You can easily get the total line count using the count() method.

```php

use cfv1000\CsvReader\Reader;

$csv = new Reader('path/to/your/file.csv');

$lineCount = count($csv); // This will return the total number of lines
echo "Total lines: " . $lineCount;
```

Note: Calling count() will move the file pointer to the end of the file. If you need to loop through the file again after counting, you must call the rewind() method first.

```php

use cfv1000\CsvReader\Reader;

$csv = new Reader('path/to/your/file.csv');

$lineCount = count($csv); 
echo "Total lines: " . $lineCount . PHP_EOL;
// Rewind the file pointer to the beginning to start reading
$csv->rewind();

foreach ($csv as $line) {
    print_r($line);
}
```
