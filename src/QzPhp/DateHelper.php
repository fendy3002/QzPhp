<?php
namespace QzPhp;

class DateHelper{

    /**
     * Generate date array for each day between two date
     * @var DateTime $periodFrom start date
     * @var DateTime $periodTo end date
     * @return DateTime[] Date array consist of date objects between period 
     */
    public function dateRangeToArray(\DateTime $periodFrom, \DateTime $periodTo){
        $diffInDays = $periodTo->diff($periodFrom)->format("%a");
        $result = [];
        $period = $periodFrom;
        for($day = 0; $day <= $diffInDays; $day++){
            // clone the date
            $result[] = clone $period;
            $period->add(new \DateInterval("P1D"));
        }
        return $result;
    }
    /**
     * Generate date array for each day for duration given
     * @var DateTime $periodFrom start date
     * @var int $duration how long
     * @return DateTime[] Date array consist of date objects for duration 
     */
    public function dateDurationToArray(\DateTime $periodFrom, $duration){
        $result = [];
        $period = $periodFrom;
        for($day = 0; $day < $duration; $day++){
            $result[] = clone $period;
            $period->add(new \DateInterval("P1D"));
        }
        return $result;
    }
}
