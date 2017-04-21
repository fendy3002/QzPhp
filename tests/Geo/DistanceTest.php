<?php
namespace Test\Geo;

class DistanceTest extends \Tests\TestCase
{
    /**
     * Test between Bundaran HI and Kebun Raya Bogor
     * @return void
     */
    public function test()
    {
        $bundaranHI = (object)[
            "latitude" => -6.194928,
            "longitude" => 106.823067
        ];

        $kebunRayaBogor = (object)[
            "latitude" => -6.597651,
            "longitude" => 106.799534
        ];

        $distance = \QzPhp\Q::Z()->geo()->distanceInKmByPoint($bundaranHI, $kebunRayaBogor);
        //print_r("distance: " . $distance . " km");

        $estimatedDistanceInKm = 44.86;
        $differenceWithEstimate = abs($estimatedDistanceInKm - $distance);
        $isLessThan100Meter = $differenceWithEstimate < 0.1;

        $this->assertTrue($isLessThan100Meter, "Distance comparison is longer than 100 meter: " . $differenceWithEstimate . " km");
    }

    /**
     * Test between two hotels in legian bali
     * @return void
     */
    public function testHotel()
    {
        $harmonyLegian = (object)[
            "latitude" => -8.711657,
            "longitude" => 115.172725
        ];

        $akmaniLegian = (object)[
            "latitude" => -8.712727,
            "longitude" => 115.172945
        ];

        $distance = \QzPhp\Q::Z()->geo()->distanceInKmByPoint($harmonyLegian, $akmaniLegian);
        //print_r("distance: " . $distance . " km");

        $estimatedDistanceInKm = 0.1;
        $differenceWithEstimate = abs($estimatedDistanceInKm - $distance);
        $isLessThan100Meter = $differenceWithEstimate < 0.1;

        $this->assertTrue($isLessThan100Meter, "Distance comparison is longer than 100 meter: " . $differenceWithEstimate . " km");
    }

    /**
     * Test between Bundaran HI and Kebun Raya Bogor
     * @return void
     */
    public function testHotel2()
    {
        $hotel1 = (object)[
            "latitude" => -6.9265499,
            "longitude" => 107.63618
        ];

        $hotel2 = (object)[
            "latitude" => -6.92702327665,
            "longitude" => 107.63574957848
        ];

        $distance = \QzPhp\Q::Z()->geo()->distanceInKmByPoint($hotel1, $hotel2);
        //print_r("distance: " . $distance . " km");

        $estimatedDistanceInKm = 0.1;
        $differenceWithEstimate = abs($estimatedDistanceInKm - $distance);
        $isLessThan100Meter = $differenceWithEstimate < 0.1;

        $this->assertTrue($isLessThan100Meter, "Distance comparison is longer than 100 meter: " . $differenceWithEstimate . " km");
    }
}
