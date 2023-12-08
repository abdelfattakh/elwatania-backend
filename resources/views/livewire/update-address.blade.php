<div class="main-about">

    <form method="post" action="{{ route('update_address',['id' => $address->getKey()]) }}" class="row g-3">
        @csrf
        <div class="col-md-4">
            <label for="first_name" class="form-label"> {{__('web.first_name')}}</label>
            <input name="name" type="text" placeholder="{{__('web.first_name')}}" class="form-control"
                   value="{{old('family_name',$address->name)}}" id="first_name">
                 @include('alerts.inline-error', ['field' => 'name'])
        </div>

        <div class="col-md-4">
            <label for="family_name" class="form-label">{{__('web.last_name')}}</label>
            <input name="family_name" type="text" placeholder="{{__('web.last_name')}}" class="form-control"
                   value="{{old('family_name',$address->family_name)}}" id="family_name">
            @include('alerts.inline-error', ['field' => 'family_name'])

        </div>
        <div class="col-md-4">
            <label for="phone" class="form-label">{{__('web.phone')}}</label>
            <input name="phone" type="text" placeholder={{__('web.write_phone')}} class="form-control"
                   value="{{old('family_name',$address->phone)}}" id="phone">
            @include('alerts.inline-error', ['field' => 'phone'])

        </div>
        <div class="col-md-6">
            <label for="city" class="form-label">{{__('web.city')}}</label>

            <select wire:model="selectedCity" name="city_id" type="text"
                    wire:change="updateSelectedCity"
                    placeholder={{__('web.write_city')}} class="form-control"
                    id="city">
                <option value="">{{__('web.select_city')}}</option>
                @foreach($cities as $city)
                    <option value="{{$city->id}}">{{$city->name}}</option>
                @endforeach
            </select>
            @include('alerts.inline-error', ['field' => 'city_id'])

        </div>
        <div class="col-md-6">
            <label for="area" class="form-label">{{__('web.area')}}</label>

            <select name="area_id" wire:model="selectedArea" type="text"
                    placeholder={{__('web.write_area')}} class="form-control"
                    id="area">
                @forelse($areas as $area)
                    <option value="{{$area->id}}">{{$area->name}}</option>
                @empty
                    <option value="null" disabled>{{__('web.select_area')}}</option>
                @endforelse
            </select>
            @include('alerts.inline-error', ['field' => 'area_id'])

        </div>
        <div class="col-md-10">
            <label for="address_details" class="form-label">{{__('web.address_details')}}</label>
            <input name="street_name" type="text" value="{{old('street_name',$address->street_name)}}"
                   placeholder={{__('web.write_address_details')}} class="form-control" id="address_details">
            @include('alerts.inline-error', ['field' => 'area_id'])

        </div>
        <div class="col-md-3">
            <label for="building_no" class="form-label">{{__('web.building_no')}}</label>
            <input name="building_no" type="text" placeholder="{{__('web.write_building_no')}}"  class="form-control"
                   value="{{old('building_no',$address->building_no)}}" id="building_no">
            @include('alerts.inline-error', ['field' => 'building_no'])

        </div>
        <div class="col-md-3">
            <label for="level_no" class="form-label">{{__('web.level_no')}}</label>
            <input name="level" type="text" placeholder="{{__('web.write_level_no')}}" class="form-control" id="level_no"
                   value="{{old('level',$address->level)}}">
            @include('alerts.inline-error', ['field' => 'level'])

        </div>
        <div class="col-md-3">
            <label for="flat_no" class="form-label">{{__('web.flat_no')}}</label>
            <input name="flat_no" type="text" placeholder="{{__('web.write_flat_no')}}" class="form-control" id="flat_no"
                   value="{{old('flat_no',$address->flat_no)}}">
            @include('alerts.inline-error', ['field' => 'flat_no'])

        </div>
        <div class="col-12">
            <button wire:loading.attr="disabled" type="submit" class="btn "> {{__('web.save')}}</button>
        </div>
        <div wire:loading>
            Hold on
        </div>
        <div class="col-12">
            <a href="{{route('forms')}}">{{__('web.cancel')}}</a>
        </div>
    </form>
</div>
