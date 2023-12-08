<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Coupons;

class CreateOrder extends CreateRecord
{

    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $setting=new GeneralSettings();
        $tax_perc=(int)$setting->tax_price;
        $tax=$tax_perc/100;
        $address=Address::find($data['address_id']);
          $data['address_name']=$address->address_name;
          $data['address_phone']=$address->address_details;
          $data['address_country_name']=$address->country->name;
          $data['address_city_name']=$address->city->name;
          $data['address_area_name']=$address->area->name;
          $data['address_details']=$address->address_details;
          $sub_total=0;
          $products=Product::whereIn('id',$data['products'])->get();
           foreach($products as $product){
               $sub_total+=$product->price;

           }
           $data['sub_total']=$sub_total;

          $coupon_value=Coupon::find((int)$data['coupon_id'])->value;

           $data['total']=($sub_total+($sub_total*$tax))-$coupon_value;

        return parent::mutateFormDataBeforeCreate($data);
    }
}
