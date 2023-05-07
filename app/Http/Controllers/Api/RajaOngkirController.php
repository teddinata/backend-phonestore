<?php
namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    /**
     * getProvinces
     *
     * @param  mixed $request
     * @return void
     */
    public function getProvinces()
    {
        $provinces = Province::all();
        return response()->json([
            'success' => true,
            'message' => 'List Data provinces',
            'data'    => $provinces
        ]);
    }

    /**
     * getCities
     *
     * @param  mixed $request
     * @return void
     */
    public function getCities(Request $request)
    {
        // $city = City::with('province')->where('province_id', $request->province_id)->get();
        // $city = \DB::select('select * from cities where province_id = 10');
        // use query builder to get data by request province_id
        // $city = \DB::select(`select * from cities where province_id  = '.$request->province_id'`);
        $city = \DB::select('select * from cities where province_id  = ?', [$request->province_id]);
        dd($request->province_id);
        // dd($request->province_id);
        // $city = \DB::table('cities')->where('province_id', $request->province_id)->get();
        // dd request params
        // dd($request->province_id);
        // $city = City::where('province_id', $request->province_id)->get();
        // $city = City::find(1);
        // $city = \DB::table('cities')->where('province_id', $request->province_id)->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Kota By Provinsi ' . $request->province_id,
            'data'    => $city
        ]);
    }

    /**
     * checkOngkir
     *
     * @param  mixed $request
     * @return void
     */
    public function checkOngkir(Request $request)
    {
        //Fetch Rest API
        $response = Http::withHeaders([
            //api key rajaongkir
            'key'          => config('services.rajaongkir.key')
        ])->post('https://api.rajaongkir.com/starter/cost', [

            //send data
            'origin'      => 113, // ID kota Demak
            'destination' => $request->city_destination,
            'weight'      => $request->weight,
            'courier'     => $request->courier
        ]);


        return response()->json([
            'success' => true,
            'message' => 'List Data Cost All Courir: '.$request->courier,
            'data'    => $response['rajaongkir']['results'][0]
        ]);
    }
}
