<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Address;
use App\Models\City;
use App\Models\Town;

class AddressController extends Controller
{

    public function getAddresses()
    {
        $addresses = Address::where('user_id', auth()->user()->id)->get();

        return response()->json($addresses, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function add(Request $request)
    {
        $address = new Address;
        $address->user_id=auth()->user()->id;
        $address->address_name=$request->address_name;
        $address->receiver_name=$request->receiver_name;
        $address->phone=$request->phone;
        $address->address=$request->address;
        $address->city=$request->city;
        $address->town=$request->town;
        $address->save();

        return response()->json($address, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function delete($id)
    {
        $address = Address::where('id', $id);
        $address->delete();
    }

    public function getCities()
    {
        $cities = City::get();
        return response()->json($cities, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function getTowns(Request $request)
    {
        $cities = Town::where('il', $request->city)->get();
        return response()->json($cities, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

}
