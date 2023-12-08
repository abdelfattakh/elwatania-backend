<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\City;
use Livewire\Component;

class UpdateAddress extends Component
{


    public $cities;
    public $areas;
    public $address;

    public $selectedCity = null;
    public $selectedArea = null;

    /**
     * @return void
     */
    public function mount($address)
    {
        $this->cities = City::active()->get();
        $this->areas = collect();
        $this->address=$address;
    }

    /**
     * render updateAddress LiveWire component
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.update-address');
    }

    /**
     * @return void
     */
    public function updateSelectedCity()
    {
        $this->areas = Area::where('city_id', $this->selectedCity)->get();
        $this->selectedArea = Null;
    }

}
