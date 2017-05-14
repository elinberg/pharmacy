<?php


namespace App\Http\Controllers;

use App\Pharmacy;
use Illuminate\Http\Request;



class PharmacyController extends Controller
{
    /**
     * Display a listing paharmacies with distance information.
     *
     * var lat string latitude of origin
     * var long string longitude of origin
     * var limit integer limit the results
     * @return \Illuminate\Http\Response
     */
    public function index($lat,$long,$limit=0)
    {
                
        //Query pharmacy table for list of nearest pharmacies
        $pharmacies = Pharmacy::getNearestPharmacy($lat,$long,10);

        //Increase the accuracy of the Spatial calculations by running them through google distance matrix
        $googleResponse = Pharmacy::distanceMatrix( $pharmacies,$lat,$long,$limit);
        print json_encode($googleResponse);

        
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
