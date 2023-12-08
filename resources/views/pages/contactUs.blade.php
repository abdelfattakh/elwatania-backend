@extends('layout.app')
@section('body')

    <!--start main page-->
    <div class="contact ">
        <div class="container">
            <div class="part1">
                <form method="post" action="{{route('general.contactUs.submit')}}">
                    @csrf
                    <h2> {{__('web.contactUs')}}</h2>
                    <div class="form-row row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4"> {{__('web.first_name')}}</label>
                            <input name="first_name" type="text" class="form-control" id="inputEmail4"
                                   placeholder=" {{__('web.first_name')}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4"> {{__('web.last_name')}}</label>
                            <input name="last_name" type="text" class="form-control" id="inputPassword4"
                                   placeholder="{{__('web.last_name')}}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputemail"> {{__('web.email')}}</label>
                        <input name="email" type="email" class="form-control" id="inputemail"
                               placeholder="{{__('web.email')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress2">{{__('web.message')}}</label>
                        <textarea name="message" class="form-control" id="inputAddress2"
                                  placeholder=" {{__('web.write_message')}}" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary a-btn">{{__('web.send')}}</button>
                </form>
            </div>
            <div class="part2">
                <a  href = "tel: {{$phone}}">
                    <img src="{{asset('frontend/images/call_btn.svg')}}" alt="">
                    <div>
                        <h3>{{__('web.contact_phone')}}</h3>
                        <h4>{{$phone}}</h4>
                    </div>
                </a>
                <a href = "mailto: {{$email}}">
                    <img src="{{asset('frontend/images/email_btn.svg')}}" alt="">
                    <div>
                        <h3> {{__('web.contact_email')}}</h3>
                        <h4>{{$email}}</h4>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!--end main page-->
@endsection
