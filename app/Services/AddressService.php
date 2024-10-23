<?php
namespace App\Services;

use App\Models\Address;


class AddressService {
    public function Store_Order_Address($request, $user_id){
        $address= Address::updateOrCreate(
            ['user_id' =>$user_id ],
            [
                'name'=>$request->name,
                'phone'=>$request->phone,
                'locality'=>$request->locality,
                'address'=>$request->address,
                'city'=>$request->city,
                'state'=>$request->state,
                'landmark'=>$request->landmark,
                'zip'=>$request->zip,
                'country'=>'',
                'user_id'=>$user_id,
                'isdefault'=>true,
            ]
        );
        return ['address'=>$address];
    }

}
