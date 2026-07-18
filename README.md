# Baby Tracker Data Parser
PHP parser for data exported from the 
[Baby Tracker Android app](https://play.google.com/store/apps/details?id=com.amila.parenting&hl=en_US)
by [Amila](https://play.google.com/store/apps/dev?id=5094777365301165062)

Supports PHP 7.1 through current PHP releases and Illuminate 5.1 through 13. PHP 7.1 is the effective minimum already required by UXDM. Invalid archives, missing `BabyRecords.csv` files, malformed rows, and unknown record categories now produce descriptive exceptions.

```php
use JordJD\BabyTrackerDataParser\Parser;

$records = (new Parser('/path/to/BabyTracker.zip'))->parseBabyRecords();

foreach ($records as $record) {
    echo $record->startDate.' '.$record->duration.' minutes'.PHP_EOL;
}
```
