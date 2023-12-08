<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\City;
use Livewire\Component;

class Forms extends Component
{
    public $cities;
    public $areas;

    public $selectedCity = null;
    public $selectedArea = null;

    public function mount()
    {
        $this->cities = City::active()->get();
        $this->areas = collect();
    }

    public function render()
    {
        return view('livewire.forms');
    }

    public function updateSelectedCity()
    {
        $this->areas = Area::where('city_id', $this->selectedCity)->get();
        $this->selectedArea = Null;
    }

}
