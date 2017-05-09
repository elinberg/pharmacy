<?php


namespace App\Http\Controllers;

use App\Pharmacy;
use Illuminate\Http\Request;
use \GoogleMaps;


class PharmacyController extends Controller
{
    /**
     * Display a listing paharmacies with distance information.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lat,$long,$limit=0)
    {
        
        //$lat='39.24387000';
        //$long='-94.44186400';
        
        //Query pharmacy table for list of pharmacies
        $pharmacies = Pharmacy::where('latitude','!=','')
        ->where('longitude','!=','')
        ->orderBy('name', 'desc')->get();
        $latlongs= array();
        $pharms= array();
        foreach($pharmacies as $pharmacy){
            $pharms[]=array(
                'name' => $pharmacy->name,
                'address' => $pharmacy->address,
                'city' => $pharmacy->city,
                'state' => $pharmacy->state,
                'zip' => $pharmacy->zip,
                'lat' => $pharmacy->latitude,
                'long' => $pharmacy->longitude
            );
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

        $distanceResponse =json_decode($response);
        $i = 0;
        $stores = array();
        foreach($distanceResponse->rows[0]->elements as $element){
            $name = $pharms[$i]['name'];
            $address = $pharms[$i]['address'];
            $city = $pharms[$i]['city'];
            $state = $pharms[$i]['state'];
            $zip = $pharms[$i]['zip'];
            $lat = $pharms[$i]['lat'];
            $long = $pharms[$i]['long'];

            $stores[$distanceResponse->destination_addresses[$i]]=(int)$element->distance->value;
            $dbStores[$distanceResponse->destination_addresses[$i]] = array(
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'lat' => $lat,
            'long' => $long,
            'distance' => $element->distance->text,
            'meters' => (int)$element->distance->value
            );
            
            //print $element->distance->text . "<br>\n";
            $i++;
        }
        //sort array by distances
        asort($stores);

        //results are ordered according to the original destinations order
        //we need to reassociate the destinations with the results
        //not optimal but it works and better then making multiple
        //api calls
        $i=0;
        $jsonArray = array();
        foreach($stores as $key=> $val){
            $jsonArray[]=array(
                'name' => $dbStores[$key]['name'],
                'address' => $dbStores[$key]['address'],
                'city' => $dbStores[$key]['city'],
                'state' => $dbStores[$key]['state'],
                'zip' => $dbStores[$key]['zip'],
                'lat' => $lat,
                'long' => $long,
                'distance' =>$dbStores[$key]['distance'],
                'meters' => $dbStores[$key]['meters']
            );
            if($limit > 0 && $limit == $i + 1 ){
                break;
            }
            $i++;
        }

        print json_encode($jsonArray);

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
