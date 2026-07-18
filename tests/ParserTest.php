<?php

namespace JordJD\BabyTrackerDataParser\Tests;

use JordJD\BabyTrackerDataParser\BabyRecords\FeedingRecord;
use JordJD\BabyTrackerDataParser\Enums\Breast;
use JordJD\BabyTrackerDataParser\Enums\FeedingType;
use JordJD\BabyTrackerDataParser\Parser;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    public function testParsesBabyTrackerArchive()
    {
        $archive = $this->archiveWith('BabyRecords.csv', implode("\n", [
            'RecordCategory,RecordSubCategory,StartDate,FinishDate,Details',
            'Feeding,Left Breast,2026-01-01 10:00:00,2026-01-01 10:30:00,',
        ]));

        $records = (new Parser($archive))->parseBabyRecords();

        $this->assertCount(1, $records);
        $this->assertInstanceOf(FeedingRecord::class, $records[0]);
        $this->assertSame(FeedingType::BREAST, $records[0]->feedingType);
        $this->assertSame(Breast::LEFT_BREAST, $records[0]->breast);
        $this->assertSame(30, $records[0]->duration);
    }

    public function testReportsMissingRecordsFile()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('BabyRecords.csv was not found');

        new Parser($this->archiveWith('Other.csv', 'value'));
    }

    private function archiveWith($name, $contents): string
    {
        $file = tempnam(sys_get_temp_dir(), 'baby-tracker-');

        $archive = new \ZipArchive();
        $archive->open($file, \ZipArchive::CREATE);
        $archive->addFromString($name, $contents);
        $archive->close();

        return $file;
    }
}
