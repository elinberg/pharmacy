<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use \DB;
use \GoogleMaps;

class Pharmacy extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pharmacies';

    /**
     * Use the distance function to return the top 10 nearest destinations.
     *
     * @var lat string  latitude of origin
     * @var long string longitude of origin
     * @var limit int limit number of results
     * returns resultset
     */
    public static function getNearestPharmacy($lat,$long,$limit=10){

        $sql = "distance(POINT(?,?),point) dist, name, address,city,state,zip,latitude,longitude";
        return Pharmacy::selectRaw($sql)->take($limit)->orderBy('dist', 'ASC')->setBindings([$lat,$long])->get();

    }
    /**
     * Use the Google distance matrix to refine the results of the MySQL lookup that uses GeoSpacial features.
     * I found that no matter what formula I used. None of them are as accurate as Google API. So I combined them.
     *
     * @var pharmacies Collection  collection of pharmacies as destinations
     * @var lat string longitude of origin
     * @var long string longitude of origin
     * @var limit int limit number of results
     * returns resultset
     */
    public static function distanceMatrix( Collection $pharmacies, $lat, $long,$limit=5){
        
        $latlongs= array();

        //create an array of lat longs from pharmacies collection
        foreach($pharmacies as $pharmacy){
            $latlongs[]="{$pharmacy->latitude},{$pharmacy->longitude}";
        }

        //Grab a an ordered list of destinations from google distance matrix
        $latlng = "$lat,$long";
        $destinations=implode('|',$latlongs);
        $response = GoogleMaps::load('distancematrix')
        ->setParam ([
            'destinations'    => $destinations,
            'origins'    => $latlng,
            'language' => 'en',
            'units' => 'imperial'
                ])->get();

        // distancematrix response returns an object with separate arrays of distination addresses and rows containing
        // distance and duration objects . The are related by the index of each array
        $distanceResponse =json_decode($response);

        $i = 0;
        $stores = array();
        foreach($distanceResponse->rows[0]->elements as $element){

            $stores[$distanceResponse->destination_addresses[$i]] = array(
            'name' => $pharmacies[$i]['name'],
            'address' => $pharmacies[$i]['address'],
            'city' => $pharmacies[$i]['city'],
            'state' => $pharmacies[$i]['state'],
            'zip' => $pharmacies[$i]['zip'],
            'lat' => $pharmacies[$i]['latitude'],
            'long' => $pharmacies[$i]['longitude'],
            'distance' => $element->distance->text,
            'meters' => (int)$element->distance->value
            );
            if($limit > 0 && $limit == $i + 1 ){
                break;
            } 
            //print $element->distance->text . "<br>\n";
            $i++;
        }
        // sort the array asc by the distance in meters
        usort($stores, function ($item1, $item2) {
            if ($item1['meters'] == $item2['meters']) return 0;
            return $item1['meters'] < $item2['meters'] ? -1 : 1;
        });

        return $stores;
    }
}
