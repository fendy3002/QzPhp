<?php
namespace QzPhp;

class Geo{
    public function distanceInKmByPoint($point1, $point2){
        $lat1 = !empty($point1->latitude) ? $point1->latitude : $point1->lat;
        $lon1 = !empty($point1->longitude) ? $point1->longitude : $point1->lon;
        $lat2 = !empty($point2->latitude) ? $point2->latitude : $point2->lat;
        $lon2 = !empty($point2->longitude) ? $point2->longitude : $point2->lon;
        return $this->distanceInKm($lat1, $lon1, $lat2, $lon2);
    }
    
    public function distanceInKm($lat1, $lon1, $lat2, $lon2){
        $earthRadiusinKm = 6371;
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);

        $radLatDistance = deg2rad($lat2 - $lat1);
        $radLonDistance = deg2rad($lon2 - $lon1);

        $a = sin($radLatDistance / 2) * sin($radLatDistance / 2) +
            cos($radLat1) * cos($radLat2) *
            sin($radLonDistance / 2) * sin($radLonDistance / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadiusinKm * $c;
    }
}
