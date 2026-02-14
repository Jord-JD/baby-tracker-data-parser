<?php

namespace JordJD\BabyTrackerDataParser\BabyRecords;


use JordJD\BabyTrackerDataParser\Enums\DiaperingType;

class DiaperingRecord extends BaseRecord
{
    public $diaperingType;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $subcategory = $this->getRecordSubCategory();

        if (str_contains($subcategory, 'Pee') && str_contains($subcategory, 'Poo')) {

            $this->diaperingType = DiaperingType::PEE_AND_POO;

        } elseif (str_contains($subcategory, 'Pee')) {

            $this->diaperingType = DiaperingType::PEE;

        } elseif (str_contains($subcategory, 'Poo')) {

            $this->diaperingType = DiaperingType::POO;

        } else {

            $this->diaperingType = DiaperingType::OTHER;

        }
    }
}