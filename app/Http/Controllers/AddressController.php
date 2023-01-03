<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Address;

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
        $address->name=$request->name;
        $address->province=$request->province;
        $address->district=$request->district;
        $address->street=$request->street;
        $address->save();

        return response()->json($address, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function delete($id)
    {
        $address = Address::where('id', $id);
        $address->delete();
    }

}
