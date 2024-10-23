<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\AddressService;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;

class AddressController extends Controller
{
    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($request, $user_id)
    {
        $response = $this->addressService->Store_Order_Address($request, $user_id);

        // $address = new Address();
        // $address->name = $request->name;
        // $address->phone = $request->phone;
        // $address->locality = $request->locality;
        // $address->address = $request->address;
        // $address->city = $request->city;
        // $address->state = $request->state;
        // $address->landmark = $request->landmark;
        // $address->zip = $request->zip;
        // $address->country = '';
        // $address->user_id = $user_id;
        // $address->isdefault = true;
        // $address->save();
        return $response['address'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        //
    }
}
