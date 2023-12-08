@extends('layout.app')
@section('body')

    <div class="main ">
        <div class="container">
            @include('cards.sidebarInfo', ['page' => 'address'])

            <div class="main-about">
                @forelse(auth()->user()->addresses as $address)
                    <div class="part double-part"
                         onclick="window.location.href=`{{route('pay',['address_id'=>$address->id])}}`"
                         id="selectedAddress">

                        <div class="part1">
                            <h2> {{__('web.address_label')}} </h2>
                            <ul>
                                <li>
                                    <p>{{__('web.name')}}</p>
                                    <h3>{{$address->name.' '.$address->family_name}}</h3>
                                </li>
                                <li>
                                    <p>{{__('web.address_label')}}</p>
                                    <h3>{{$address->street_name}}</h3>
                                </li>
                                <li>
                                    <p>{{__('web.phone_number')}}</p>
                                    <h3>{{$address->phone}}</h3>
                                </li>
                            </ul>
                        </div>
                        <div class="part2">

                            <a href="{{route('updateaddress',['id'=>$address->id])}}">
                                <input type="submit" class="update" value="{{__('web.update')}}"/>
                            </a>

                            <form method="post" action="{{ route('delete_address', $address->id) }}"
                                  class="m-0">
                                @csrf
                                <input type="submit" class="delete" value="{{__('web.delete')}}"/>
                            </form>
                        </div>
                    </div>
                    </a>
                @empty
                    <div class="part">
                        <h2> {{__('web.address')}}</h2>
                        <p>{{__('web.add_address_recommendation')}}</p>

                    </div>
                @endforelse
                <a href="{{route('forms')}}">
                    <button> {{__('web.add_address')}}</button>
                </a>
            </div>

        </div>


        @endsection



        @push('scripts')
            <script>
                const freeclass = document.querySelectorAll('.double-part');
                for (var i = 0; i < freeclass.length; i++) {
                    freeclass[i].addEventListener('click', (e) => {
                        if (e.target.classList.contains("double-part")) {
                            freeclass.forEach(function (el) {
                                el.classList.remove('active_ad');
                            });
                            e.target.classList.add('active_ad');
                        }
                    });
                }
                // #####
                {{-- function selectedAddress(address_id){--}}
                {{--     console.log(address_id)--}}
                {{--    let url=`{{route('pay',':address_id')}}`;--}}
                {{--    url.replace(':address_id',address_id)--}}
                {{--     window.location.href=url;--}}
                {{-- }--}}
            </script>
    @endpush
