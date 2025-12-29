<!-- banner -->
<section class="container-fluid pb-3"
         style="background-image: linear-gradient(rgba(13, 13, 13, 0.5), rgba(6, 0, 0, 0.5)), url('https://asapexpressinc.com/wp-content/uploads/2020/08/AdobeStock_337982225-scaled.jpeg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;">
  <div style="max-width: 1142px;
    margin-left: auto;   
    margin-right: auto;
    padding-left: 15px; 
    padding-right: 15px;
    padding-top: 3rem;  
    padding-bottom: 3rem;">
        <div class="row align-items-center mt-3">
            
                <!-- Additional track & trace block -->
                <div class=" py-5 text-center ">
                    <h1 class="text-primary mb-4">Safe & Faster</h1>
                    <h1 class="text-light display-3 mb-">We Make Moving</h1>
                    <div class="container px-0">
                        <h3 class="text-center text-white" >Track & Trace</h3>
                        <form action="{{ route('tracking.index') }}" method="get">
                            <div class="input-group mb-3 tracking-formT">
                                <input type="text" class="form-control" placeholder="{{ __('levels.enter_tracking_id') }}" name="tracking_id">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text bg-danger">{{ __('levels.track_now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            
        </div>
    </div>
</section>