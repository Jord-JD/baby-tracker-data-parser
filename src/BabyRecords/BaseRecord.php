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
        foreach (['RecordCategory', 'StartDate', 'FinishDate'] as $requiredField) {
            if (!isset($data[$requiredField]) || $data[$requiredField] === '') {
                throw new \UnexpectedValueException('Baby Tracker row is missing required field: '.$requiredField);
            }
        }

        $this->recordCategory = $data['RecordCategory'];
        $this->recordSubCategory = isset($data['RecordSubCategory']) ? $data['RecordSubCategory'] : null;
        $this->startDate = new Carbon($data['StartDate']);
        $this->finishDate = new Carbon($data['FinishDate']);
        $this->details = isset($data['Details']) ? $data['Details'] : null;
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
