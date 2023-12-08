<?php

namespace App\Http\Controllers\Web\Address;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\CreateAddressRequest;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Http\Requests\web\Address\AddressRequest;
use App\Http\Requests\web\Address\UpdateAddressWebRequest;
use App\Models\Address;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    /**
     * create Delivery Address
     * @param AddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_Address(AddressRequest $request)
    {

        $validated = $request->validated();
        $country = City::find($request->validated('city_id'))->country->getKey();
        data_set($validated, 'country_id', $country);
        $address = auth()->user()->addresses()->create($validated);

        return redirect()->route('address');
    }

    /**
     * update Delivery Address
     * @param UpdateAddressWebRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_address(UpdateAddressWebRequest $request, $id)
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

        return redirect()->route('address');

    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete_address($id)
    {
        $address = Address::find($id);
        $address->delete();

        return redirect()->route('address');

    }
}
