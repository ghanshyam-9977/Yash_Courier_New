@extends('frontend.layouts.master')
@section('title')
    {{ __('levels.parcel_tracking') }} | {{ @settings()->name }}
<<<<<<< HEAD
@endsection
@section('content')
<section class="container-fluid pb-5  ">
    <div class="container pt-5 pb-5 ">
        <div class="row align-items-center mt-3">
            <div class="col-lg-8 m-auto">
                <form action="{{ route('tracking.index') }}" method="GET">

=======
@endsection 
@section('content') 
<section class="container-fluid pb-5  ">
    <div class="container pt-5 pb-5 ">
        <div class="row align-items-center mt-3">
            <div class="col-lg-8 m-auto"> 
                <form action="{{ route('tracking.index') }}" method="GET">
                   
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                    <div class="input-group mb-3 tracking-page tracking-form">
                        <input type="text" class="form-control" placeholder="{{ __('levels.enter_tracking_id') }}" name="tracking_id" value="{{ $request->tracking_id }}"  >
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text bg-primary"  >{{ __('levels.track_now') }}</button>
                        </div>
                    </div>
                </form>
                <h3 class="font-size-1-5rem display-6 font-weight-bold text-center my-4">{{ __('levels.parcel_tracking_no') }}: <span class="text-primary"># {{ $request->tracking_id }}</span></h3>
<<<<<<< HEAD
            </div>
        </div>
        <div class="parcel-oprations">
            @if(!empty($request->tracking_id) && $parcel)
            <section class="cd-timeline js-cd-timeline">
                <div class="cd-timeline__container">
                    @foreach ($parcelevents as $key=>$log)
                        @php
                            if(!empty($log->cancel_parcel_id)):
                                $cancel   = 'cancel';
                                $danger   = 'danger';
                            else:
                                $cancel   = null;
                                $danger   = null;
                            endif;

                        @endphp

                        @switch($log->parcel_status)
                            @case(\App\Enums\ParcelStatus::PICKUP_ASSIGN)

                                <div class="cd-timeline__block js-cd-block">
                                        <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                            <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                        </div>

=======
            </div>  
        </div> 
        <div class="parcel-oprations"> 
            @if(!empty($request->tracking_id) && $parcel)
            <section class="cd-timeline js-cd-timeline">
                <div class="cd-timeline__container">  
                    @foreach ($parcelevents as $key=>$log)
                        @php
                            if(!empty($log->cancel_parcel_id)): 
                                $cancel   = 'cancel';
                                $danger   = 'danger'; 
                            else:
                                $cancel   = null;
                                $danger   = null; 
                            endif; 
                           
                        @endphp
        
                        @switch($log->parcel_status)
                            @case(\App\Enums\ParcelStatus::PICKUP_ASSIGN)
        
                                <div class="cd-timeline__block js-cd-block"> 
                                        <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                            <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                        </div>
                                    
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('parcel.pickup_man')}}: {{isset($log->pickupman)? $log->pickupman->user->name:''}}</span><br>
                                        <span>{{__('levels.mobile')}}: {{isset($log->pickupman)? $log->pickupman->user->mobile:''}}</span><br>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>

=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
                            @case(\App\Enums\ParcelStatus::PICKUP_RE_SCHEDULE)
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__yellow js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas fa-hourglass-end {{ isset($danger)? 'fa-close':'fa-hourglass-end' }}" aria-hidden="true"></i>
                                    </div>
<<<<<<< HEAD

=======
         
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('parcel.pickup_man')}}: {{isset($log->pickupman)? $log->pickupman->user->name:''}}</span><br>
                                        <span>{{__('levels.mobile')}}: {{isset($log->pickupman)? $log->pickupman->user->mobile:''}}</span><br>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
                            @case(\App\Enums\ParcelStatus::RECEIVED_BY_PICKUP_MAN)
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                    </div>
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
                            @case(\App\Enums\ParcelStatus::RECEIVED_WAREHOUSE)
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                    </div>
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('parcelLogs.hub_name')}}: {{$log->hub->name}}</span><br>
                                        <span>{{__('levels.mobile')}}: {{$log->hub->phone}}</span><br/>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
                            @case(\App\Enums\ParcelStatus::TRANSFER_TO_HUB)
<<<<<<< HEAD

=======
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                    </div>
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('parcelLogs.hub_name')}}: {{$log->hub->name}}</span><br>
                                        <span>{{__('parcelLogs.hub_phone')}}: {{$log->hub->phone}}</span><br/>
                                        <span>{{__('parcelLogs.delivery_man')}}: {{ isset($log->transferDeliveryman) ? $log->transferDeliveryman->user->name:''}}</span><br/>
                                        <span>{{__('parcelLogs.delivery_man_phone')}}: {{ isset($log->transferDeliveryman) ? $log->transferDeliveryman->user->mobile:''}}</span><br/>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
<<<<<<< HEAD

=======
                       
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                            @case(\App\Enums\ParcelStatus::DELIVERY_MAN_ASSIGN)
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                    </div>
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('parcelLogs.delivery_man')}}: {{isset($log->deliveryMan)? $log->deliveryMan->user->name:''}}</span><br>
                                        <span>{{__('levels.phone')}}: {{isset($log->deliveryMan)? $log->deliveryMan->user->mobile:''}}</span><br/>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
<<<<<<< HEAD

=======
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                            @case(\App\Enums\ParcelStatus::DELIVERY_RE_SCHEDULE)
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__yellow js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-hourglass-end' }}" aria-hidden="true"></i>
                                    </div>
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('parcelLogs.delivery_man')}}: {{isset($log->deliveryMan)? $log->deliveryMan->user->name:''}}</span><br>
                                        <span>{{__('levels.phone')}}: {{isset($log->deliveryMan)? $log->deliveryMan->user->mobile:''}}</span><br/>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
                            @case(\App\Enums\ParcelStatus::DELIVERED)
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                    </div>
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
                            @case(\App\Enums\ParcelStatus::PARTIAL_DELIVERED)
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                    </div>
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                            @break
                            @case(\App\Enums\ParcelStatus::RETURN_MERCHANT_RE_SCHEDULE)
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__yellow js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-hourglass-end' }}" aria-hidden="true"></i>
                                    </div>
                                   <!-- cd-timeline__img -->
                                   <div class="cd-timeline__content js-cd-content">
                                    <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                    <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                    <strong>{{ __('levels.created_by') }}</strong><br/>
                                    <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                    <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                    <strong>{{ __('levels.created_by') }}</strong><br/>
                                    <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                    <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                    <div class="cd-timeline__date">
                                        <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                        <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                    </div>
                                </div>
                                <!-- cd-timeline__content -->
                                </div>
                            @break
<<<<<<< HEAD

=======
                            
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                            @default
                                <div class="cd-timeline__block js-cd-block">
                                    <div class="cd-timeline__img cd-timeline__img--picture js-cd-img {{ isset($danger)? 'bg-danger':'' }}">
                                        <i class="timeline_icon fas {{ isset($danger)? 'fa-close':'fa-check' }}" aria-hidden="true"></i>
                                    </div>
                                    <!-- cd-timeline__img -->
                                    <div class="cd-timeline__content js-cd-content">
                                        <strong>{{__('parcelLogs.'.$log->parcel_status)}} {{ isset($danger)? $cancel:'' }}</strong><br>
                                        <span>{{__('levels.note')}}: {{$log->note}}</span><br/>
<<<<<<< HEAD

                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>


=======
        
                                        <strong>{{ __('levels.created_by') }}</strong><br/>
                                        <span>{{ __('levels.name') }}: {{ $log->user->name }} </span><br/>
                                        <span>{{ __('levels.mobile') }}: {{ $log->user->mobile }} </span><br/>
        
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                                        <div class="cd-timeline__date">
                                            <strong>{!! dateFormat($log->created_at) !!}</strong><br>
                                            <small>{!! date('h:i a', strtotime($log->created_at)) !!}</small>
                                        </div>
                                    </div>
                                    <!-- cd-timeline__content -->
                                </div>
                        @endswitch
<<<<<<< HEAD
                    @endforeach
=======
                    @endforeach 
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                    <div class="cd-timeline__block js-cd-block">
                        <div class="cd-timeline__img cd-timeline__img--picture js-cd-img">
                            <i class="timeline_icon fas fa-check" aria-hidden="true"></i>
                        </div>
                        <!-- cd-timeline__img -->
                        <div class="cd-timeline__content js-cd-content">
                            <strong>{{__('parcel.parcel_create')}}</strong><br>
                            <span>{{__('levels.name')}}: {{$parcel->merchant->user->name}}</span><br>
                            <span>{{__('levels.email')}}: {{$parcel->merchant->user->email}}</span><br>
                            <span>{{__('levels.mobile')}}: {{$parcel->merchant->user->mobile}}</span><br/>
<<<<<<< HEAD

=======
        
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                            <div class="cd-timeline__date">
                                <strong>{!! dateFormat($parcel->created_at) !!}</strong><br>
                                <small>{!! date('h:i a', strtotime($parcel->created_at)) !!}</small>
                            </div>
                        </div>
                        <!-- cd-timeline__content -->
<<<<<<< HEAD
                    </div>
                </div>
            </section>


            <!-- cd-timeline -->
            @elseif(!empty($request->tracking_id) && !$parcel)
=======
                    </div> 
                </div>
            </section>
            
            
            <!-- cd-timeline -->
            @elseif(!empty($request->tracking_id) && !$parcel) 
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
                <div class="row my-5">
                    <div class="col-lg-6 m-auto">
                        <img src="{{ static_asset('frontend/images/parcel-was-not-found.png') }}" width="100%"/>
                    </div>
<<<<<<< HEAD
                </div>
=======
                </div> 
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            @endif
            @if(isset($parcelItems) && count($parcelItems) > 0)
            <div class="container mt-5">
                <h4 class="text-center mb-4 text-primary fw-bold">Parcel Items Details</h4>
                <div class="table-responsive shadow p-3 mb-5 bg-body rounded">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>#</th>
                                <th>Barcode</th>
                                <th>Hub Name</th>
                                <th>Address</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Amount (₹)</th>
                                <th>Qty</th>
                                <th>To Branch</th>
                                <th>Transport</th>
                                <th>Unit</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parcelItems as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-dark" title="Barcode">{{ $item->barcode }}</span>
                                </td>
                                <td title="Hub Name">{{ $item->hub_name }}</td>
                                <td title="Address">{{ $item->address }}</td>
                                <td title="Customer">{{ $item->customer_name }}</td>
                                <td title="Phone">{{ $item->phone }}</td>
                                <td title="Amount"><strong>₹{{ number_format($item->amount, 2) }}</strong></td>
                                <td title="Quantity">{{ $item->quantity }}</td>
                                <td title="To Branch">{{ $item->to_branch_id }}</td>
                                <td title="Transport Type">
                                    <span class="badge bg-info text-dark text-uppercase">{{ $item->transport_type }}</span>
                                </td>
                                <td title="Unit">{{ $item->unit }}</td>
                                <td title="Created At">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <style>
            /* Mobile friendly tweaks */
            @media (max-width: 768px) {
                table thead {
                    font-size: 0.85rem;
                }
                table tbody td {
                    font-size: 0.8rem;
                    padding: 0.5rem;
                }
                .badge {
                    font-size: 0.75rem;
                    padding: 0.35em 0.45em;
                }
            }
            </style>
            @endif

        </div>
    </div>
<<<<<<< HEAD
</section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ static_asset('frontend/css/timeline.css') }}"/>
@endpush
=======
</section> 
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ static_asset('frontend/css/timeline.css') }}"/>
@endpush
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
