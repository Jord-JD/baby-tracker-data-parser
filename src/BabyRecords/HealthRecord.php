<?php

namespace JordJD\BabyTrackerDataParser\BabyRecords;

use JordJD\BabyTrackerDataParser\Enums\HealthType;

class HealthRecord extends BaseRecord
{
    public $healthType;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $subcategory = $this->getRecordSubCategory();

        if (str_contains($subcategory, 'Medication')) {

            $this->healthType = HealthType::MEDICATION;

        } else {

            $this->healthType = HealthType::OTHER;

        }
    }
}