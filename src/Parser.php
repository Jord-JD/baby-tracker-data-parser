<?php

namespace JordJD\BabyTrackerDataParser;

use JordJD\BabyTrackerDataParser\BabyRecords\BaseRecord;
use JordJD\uxdm\Objects\Destinations\AssociativeArrayDestination;
use JordJD\uxdm\Objects\Migrator;
use JordJD\uxdm\Objects\Sources\CSVSource;
use Illuminate\Support\Collection;

class Parser
{
    private $pathToZipFile;

    public function __construct(string $pathToZipFile)
    {
        if (!$pathToZipFile) {
            throw new \InvalidArgumentException('Path to zip file not specified.');
        }

        if (!file_exists($pathToZipFile)) {
            throw new \InvalidArgumentException('Zip file not found.');
        }

        $this->pathToZipFile = $pathToZipFile;
    }

    public function parseBabyRecords()
    {
        $rows = [];

        $csvSource = new CSVSource('zip://'.$this->pathToZipFile.'#BabyRecords.csv');
        $arrayDestination = new AssociativeArrayDestination($rows);

        (new Migrator())
            ->setSource($csvSource)
            ->setDestination($arrayDestination)
            ->migrate();

        $collection = new Collection();

        foreach($rows as $row) {
            $className = 'JordJD\\BabyTrackerDataParser\\BabyRecords\\'.$row['RecordCategory'].'Record';
            if (!class_exists($className)) {
                throw new \Exception('Unexpected record type found: '.$row['RecordCategory']);
            }
            $collection->push(new $className($row));
        }

        return $collection;
    }
}