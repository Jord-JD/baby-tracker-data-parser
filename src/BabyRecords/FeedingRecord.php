<?php

namespace JordJD\BabyTrackerDataParser\BabyRecords;


use JordJD\BabyTrackerDataParser\Enums\Breast;
use JordJD\BabyTrackerDataParser\Enums\FeedingType;

class FeedingRecord extends BaseRecord
{
    public $feedingType;
    public $breast;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $subcategory = $this->getRecordSubCategory();

        if (str_contains($subcategory, 'Breast')) {

            $this->feedingType = FeedingType::BREAST;

            if (str_contains($subcategory, 'Left Breast')) {
                $this->breast = Breast::LEFT_BREAST;
            } elseif (str_contains($subcategory, 'Right Breast')) {
                $this->breast = Breast::RIGHT_BREAST;
            }

        } else {

            $this->breast = Breast::NONE;

            if (str_contains($subcategory, 'Bottle')) {

                $this->feedingType = FeedingType::BOTTLE;

            } else {

                $this->feedingType = FeedingType::OTHER;

            }
        }

    }
}