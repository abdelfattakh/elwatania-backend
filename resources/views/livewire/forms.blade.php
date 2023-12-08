<div class="main-about">
    <form method="post" action="{{route('create_Address')}}" class="row g-3">
        @csrf
        <div class="col-md-4">
            <label for="first_name" class="form-label"> {{__('web.first_name')}}</label>
            <input name="name" type="text" placeholder="{{__('web.write_first_name')}}" class="form-control"
                   value="{{ old('first_name') }}" id="first_name">
            @include('alerts.inline-error', ['field' => 'name'])
        </div>

        <div class="col-md-4">
            <label for="family_name" class="form-label">{{__('web.last_name')}}</label>
            <input name="family_name" type="text" placeholder="{{__('web.write_last_name')}}" class="form-control"
                   value="{{ old('family_name') }}" id="family_name">
            @include('alerts.inline-error', ['field' => 'family_name'])
        </div>

        <div class="col-md-4">
            <label for="phone" class="form-label">{{__('web.phone')}}</label>
            <input name="phone" type="text" placeholder={{__('web.write_phone')}} class="form-control"
                   value="{{ old('phone') }}" id="phone">
            @include('alerts.inline-error', ['field' => 'phone'])
        </div>

        <div class="col-md-6">
            <label for="city" class="form-label">{{__('web.city')}}</label>
            <select wire:model="selectedCity" name="city_id" type="text"
                    wire:change="updateSelectedCity"
                    placeholder={{__('web.write_city')}} class="form-control"
                    id="city">
                @forelse($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @empty
                    <option disabled value="">{{ __('web.select') }}</option>
                @endforelse
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
                    <option disabled value="">{{ __('web.select') }}</option>
                @endforelse
            </select>
            @include('alerts.inline-error', ['field' => 'area_id'])
        </div>

        <div class="col-md-10">
            <label for="street_name" class="form-label">{{__('web.address_details')}}</label>
            <input name="street_name" type="text" value="{{ old('street_name') }}"
                   placeholder={{__('web.write_address_details')}} class="form-control" id="street_name">
            @include('alerts.inline-error', ['field' => 'street_name'])
        </div>

        <div class="col-md-3">
            <label for="building_no" class="form-label">{{__('web.building_no')}}</label>
            <input name="building_no" type="text" placeholder="{{__('web.write_building_no')}}" class="form-control"
                   value="{{ old('building_no') }}" id="building_no">
            @include('alerts.inline-error', ['field' => 'building_no'])
        </div>

        <div class="col-md-3">
            <label for="level" class="form-label">{{__('web.level_no')}}</label>
            <input name="level" type="text" placeholder="{{__('web.write_level_no')}}" class="form-control" id="level"
                   value="{{ old('level') }}">
            @include('alerts.inline-error', ['field' => 'level'])
        </div>

        <div class="col-md-3">
            <label for="flat_no" class="form-label">{{__('web.flat_no')}}</label>
            <input name="flat_no" type="text" placeholder="{{__('web.write_flat_no')}}" class="form-control" id="flat_no"
                   value="{{ old('flat_no') }}">
            @include('alerts.inline-error', ['field' => 'flat_no'])
        </div>

        <div class="col-12">
            <button wire:loading.attr="disabled" type="submit" class="btn ">{{ __('web.save') }}</button>
        </div>

        <div wire:loading>
            {{ __('web.hold_on') }}
        </div>

        <div class="col-12">
            <a href="{{ route('address') }}">{{ __('web.cancel') }}</a>
        </div>
    </form>
</div>
