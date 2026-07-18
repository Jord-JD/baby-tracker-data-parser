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
        if ($pathToZipFile === '') {
            throw new \InvalidArgumentException('Path to zip file not specified.');
        }

        if (!is_file($pathToZipFile) || !is_readable($pathToZipFile)) {
            throw new \InvalidArgumentException('Zip file not found.');
        }

        $archive = new \ZipArchive();
        $opened = $archive->open($pathToZipFile);
        if ($opened !== true) {
            throw new \InvalidArgumentException('Unable to open Baby Tracker zip file.');
        }

        if ($archive->locateName('BabyRecords.csv') === false) {
            $archive->close();
            throw new \InvalidArgumentException('BabyRecords.csv was not found in the Baby Tracker export.');
        }
        $archive->close();

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

        foreach ($rows as $row) {
            if (!isset($row['RecordCategory']) || !is_string($row['RecordCategory'])) {
                throw new \UnexpectedValueException('A Baby Tracker row is missing its record category.');
            }

            $className = 'JordJD\\BabyTrackerDataParser\\BabyRecords\\'.$row['RecordCategory'].'Record';
            if (!class_exists($className)) {
                throw new \UnexpectedValueException('Unexpected record type found: '.$row['RecordCategory']);
            }
            $collection->push(new $className($row));
        }

        return $collection;
    }
}
