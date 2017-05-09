<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class PharmaciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $file = public_path().'/csv/pharmacies.csv';
        $csv = Reader::createFromPath($file);

        $csv->setOffset(1); //because we don't want to insert the header
        //var_dump($csv);
        $results = $csv->fetch();
        foreach ($results as $row)  {
            \DB::table('pharmacies')->insert(
                array(
                    'name' => trim($row[0]),
                    'address' => trim($row[1]),
                    'city' => trim($row[2]),
                    'state' => trim($row[3]),
                    'zip' => trim($row[4]),
                    'latitude' => trim($row[5]),
                    'longitude' => trim($row[6]),
                )
            );
        }
    }
}
