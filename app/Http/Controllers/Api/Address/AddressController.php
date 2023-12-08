<?php

namespace App\Http\Controllers\Api\Address;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\CreateAddressRequest;
use App\Http\Requests\Api\Address\DeleteAddress;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Models\Address;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():JsonResponse
    {
        $addresses=$this->user->addresses()->get();

        return ApiResponse::ResponseSuccess(data:[
            'addresses'=>$addresses
        ]);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAddressRequest $request):JsonResponse
    {
        $validated = $request->validated();
        $country = City::find($request->validated('city_id'))->country->getKey();
        data_set($validated, 'country_id', $country);
        $address = $this->user->addresses()->create($validated);

        return ApiResponse::ResponseSuccess(data: [
            'Address' => $address
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id):JsonResponse
    {
        $address=Address::find($id);
        return ApiResponse::ResponseSuccess(data:[
           'address'=>$address
        ]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAddressRequest $request, $id):JsonResponse
    {
        $address = Address::find($id);

        if ($request->has('name') && $address->name != $request->input('name')) {
            $address->update([
                'name' => $request->input('name') ?: $address->name,
            ]);
        }

        if ($request->has('family_name') && $address->family_name != $request->input('family_name')) {
            $address->update([
                'family_name' => $request->input('family_name') ?: $address->family_name,
            ]);
        }
        if ($request->has('building_no') && $address->building_no != $request->input('building_no')) {
            $address->update([
                'building_no' => $request->input('building_no') ?: $address->building_no,
            ]);
        }
        if ($request->has('flat') && $address->flat != $request->input('flat')) {
            $address->update([
                'flat' => $request->input('flat') ?: $address->flat,
            ]);
        }
        if ($request->has('level') && $address->level != $request->input('level')) {
            $address->update([
                'level' => $request->input('level') ?: $address->level,
            ]);
        }
        if ($request->has('street_name') && $address->street_name != $request->input('street_name')) {
            $address->update([
                'street_name' => $request->input('street_name') ?: $address->street_name,
            ]);
        }


        return ApiResponse::ResponseSuccess(data: [
            'Address' => $address
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id):JsonResponse
{
    $address = Address::find($id);
    $address->delete();

    return ApiResponse::ResponseSuccess(message: "your address deleted successfully");
}
}
