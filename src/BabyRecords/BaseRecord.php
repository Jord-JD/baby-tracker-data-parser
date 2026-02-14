<?php

namespace JordJD\BabyTrackerDataParser\BabyRecords;


use Carbon\Carbon;

class BaseRecord
{
    private $recordCategory;
    private $recordSubCategory;
    public $startDate;
    public $finishDate;
    public $details;
    public $duration;

    public function __construct(array $data)
    {
        $this->recordCategory = $data['RecordCategory'];
        $this->recordSubCategory = isset($data['RecordSubCategory']) ? $data['RecordSubCategory'] : null;
        $this->startDate = new Carbon($data['StartDate']);
        $this->finishDate = new Carbon($data['FinishDate']);
        $this->details = $data['Details'];
        $this->duration = $this->startDate->diffInMinutes($this->finishDate);
    }

    /**
     * @return mixed|null
     */
    public function getRecordSubCategory()
    {
        return $this->recordSubCategory;
    }
}