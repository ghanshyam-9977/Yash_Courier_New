<!-- 
<section class="container-fluid  py-3 pb-0"> 
    <div class="container">
        <div class="row  mb-3">
            <div class="col-lg-8 m-auto">
                <h3 class="display-6 title text-center mb-5"><span class="section-title">{{ __('levels.our_services') }}</span></h3>
            </div>
        </div>
        <div class="row py-2 "> 
            @foreach ( $services as $service)      
                <div class="col-lg-3 col-sm-6  mb-3 text-center ">
                    <div class="service-item p-3 h-100"> 
                        <div class="service-item-box d-block">
                            <div class="my-4" >
                                <div class="service-box">
                                    <div class="icon-box d-flex"> 
                                        <img src="{{ $service->image }}" width="100%"/>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mb-3 font-weight-bold">{{ $service->title }}</h5> 
                            <p class="mb-2">{!! \Str::limit(strip_tags($service->description), 120, ' ...<p><a href="'.route('service.details',$service->id).'" class="text-primary"><i class="fa fa-arrow-right"></i></a></p>') !!}</p>  
                        </div>
                    </div>
                </div>  
            @endforeach
 
        </div>
    </div>
</section> -->




<!-- Section: Moving Service -->
<section style="padding: 60px 0; font-family: Arial, sans-serif;">
  <div class="container" style="max-width: 1500px; margin: auto;">
    <div class="row" style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap;">

      <!-- Left Image with Experience -->
      <div class="left" style="flex: 1; min-width: 300px;">
        <div style="position: relative;">
          <img src="https://webmarketingangels.com.au/wp-content/uploads/2020/11/Best-Couriers-to-Use-resized.jpg"
               alt="Truck Image"
               style="width: 100%; height: auto; border-radius: 4px;">
          <div style="background-color:   #b32400; color: white; font-weight: bold;
                      text-align: center; padding: 14px 0; font-size: 1.25rem; margin-top: -5px;">
            25+ Years Experience
          </div>
        </div>
      </div>

      <!-- Right Content -->
      <div class="right" style="flex: 1; min-width: 300px;">
      <h5 style="color: rgb(183, 47, 32) !important; font-size: 2rem; font-weight: 800;">PROVIDE BEST MOVING SERVICE</h5>
        <h2 style="font-size: 2.8rem; color: #2c3e50 ; font-weight: 800; margin-bottom: 20px; line-height: 1.2;"> We Make Moving<br>Fast & Easy   </h2>
        <p style="color: rgb(38, 37, 37); font-size: 1.2rem; line-height: 1.8;">
          Yes Courier is something New & Innovative way to send parcels across <strong>INDIA & GLOBE</strong> in best affordable price & convenience. Our services offer <strong>EASY</strong> pick-up of all types of Documents, Parcels & Luggage from your doorstep and deliver them anywhere in the country or abroad at our Client’s desired time & location.
        </p>
      </div>

    </div>
  </div>



    <!-- Form Section -->

  <div style="background-color: #f3f3f3; margin: 40px 0; padding: 60px 30px;">
  <div class="container" style="max-width: 1500px; margin: auto;">
    <div class="row" style="display: flex; align-items: stretch; gap: 30px;">
      
      <!-- Left Column: Text Content -->
      <div class="left" style="flex: 1; min-width: 300px;">
       <h5 style="color: rgb(183, 47, 32) !important; font-size: 2rem; font-weight: 800;">ABOUT US</h5>
        <h2 style="font-size: 2.5rem; font-weight: 800; color:#2c3e50; margin-bottom: 20px;">The Best Moving<br>Company In The Country</h2>
        <p style="color: rgb(38, 37, 37); font-size: 1.2rem; line-height: 1.8;">
          Our 25 years of courier & logistic experience help our clients to deliver their goods in best possible manner & helping them to save their time in searching for a ordinary courier agency.
          Our online services help our clients to get instant quotes & book their service in few EASY steps. Our specially designed MOBILE APPLICATION gives you instant Quote, Delivery timeline & Easy pick up at your doorstep. 
          Our APP will also keep on updating you the perfect status of your parcel.
        </p>

        <!-- Stats -->
        <div style="margin-top: 40px; display: flex; gap: 40px; flex-wrap: wrap;">
          <div>
            <h3 class="counter" data-target="225" style="color: rgb(183, 47, 32)!important; font-size: 2rem; font-weight: 800;">0</h3>
            <p style="margin: 0; font-size: 1.4rem; font-weight: 800;">Project Completed</p>
          </div>
          <div>
            <h3 class="counter" data-target="189" style="color: rgb(183, 47, 32)!important; font-size: 2rem; font-weight: 800;">0</h3>
            <p style="margin: 0; font-size: 1.4rem; font-weight: 800;">Packages Delivered</p>
          </div>
          <div>
            <h3 class="counter" data-target="2500"  style="color: rgb(183, 47, 32)!important; font-size: 2rem; font-weight: 800;">0</h3>
            <p style="margin: 0; font-size: 1.4rem; font-weight: 800;">Commercial Goods</p>
          </div>
        </div> 
      </div>

      <!-- Right Column: Form -->
      <div class="right" style="flex: 1; min-width: 300px;">
        <div style="height: 100%; padding: 30px; background:  #8080ff; border-radius: 8px; display: flex; flex-direction: column; justify-content: center;">
          <form style="display: flex; flex-direction: column; gap: 20px;">
            <input type="text" placeholder="Your Name" style="padding: 12px; font-size: 1rem; border: none; border-radius: 4px;">
            <input type="email" placeholder="Your Email" style="padding: 12px; font-size: 1rem; border: none; border-radius: 4px;">
            <select style="padding: 12px; font-size: 1rem; border: none; border-radius: 4px;">
              <option>Select A Service</option>
              <option>Parcel Delivery</option>
              <option>Logistics</option>
              <option>Courier Express</option>
            </select>
            <button type="submit" style="padding: 12px; background-color:    #ff471a;  color: #fff; border: none; border-radius: 4px; font-weight: bold;">  Get A Quote </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>


<div  style="max-width: 1500px; margin: auto;">
        <div class="container" style=" max-width: 1500px; margin: auto" >
            <div class="text-center pb-2">
                <h5 style="color: rgb(183, 47, 32) !important; font-size: 2rem; font-weight: 800;">WORK PROCESS</h5>
                <h2 style="font-size: 2rem; color: #2c3e50; font-weight: 300; margin-bottom: 1.5rem; line-height: 1; text-align: center; ">Make It Happens In 4 Steps</h2>
            </div>
            <div class="row ">
                <div class="service-box" style="width: 100%; max-width: 280px; margin: 0 auto 40px; text-align: center; box-shadow: 1px 2px 8px 1px  #b32400;   border-radius: 15px; padding:8px">
                   <div class="icon-container" style="background-color:  #b32400; display: flex; align-items: center; justify-content: center; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 6px;">
                        <i class="fa fa-calendar-check" style="font-size: 2rem; color: white; margin-right: 10px;"></i>
                           <h6 style="color: #ffffff!important; font-weight: 600; font-size: 1rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Book Your Service</h6>
                   </div>
                        <p style="font-size: 1rem; color: #333; line-height: 1.6;"> Scheduling your delivery is easy with Yes Courier. Simply book online or contact us, and we'll arrange a hassle-free pick-up at your convenience.  </p>
                        <!-- <a href="about.html" style="display: inline-block;  margin-top: 10px;  color: #e53935;  border-bottom: 2px solid #e53935;  padding-bottom: 2px;  text-decoration: none;  font-weight: 600;  transition: 0.3s ease;">Read More</a> -->
                </div>
                <div class="service-box" style="width: 100%; max-width: 280px; margin: 0 auto 40px; text-align: center; box-shadow: 1px 2px 8px 1px #b32400;   border-radius: 15px; padding:8px ">
                   <div class="icon-container" style="background-color: #b32400; display: flex; align-items: center; justify-content: center; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 6px;">
                        <i class="fa fa-calendar-check" style="font-size: 2rem; color: white; margin-right: 10px;"></i>
                           <h6 style="color: #ffffff!important; font-weight: 600; font-size: 1rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Pack Your Good</h6>
                   </div>
                        <p style="font-size: 1rem; color: #333; line-height: 1.6;"> Ensure your items are securely packed for safe transportation. We provide guidelines and packaging support to keep your goods protected during transit.  </p>
                        <!-- <a href="about.html" style="display: inline-block;  margin-top: 10px;  color: #e53935;  border-bottom: 2px solid #e53935;  padding-bottom: 2px;  text-decoration: none;  font-weight: 600;  transition: 0.3s ease;">Read More</a> -->
                </div>
                <div class="service-box" style="width: 100%; max-width: 280px; margin: 0 auto 40px; text-align: center; box-shadow: 1px 2px 8px 1px #b32400;   border-radius: 15px; padding:8px">
                   <div class="icon-container" style="background-color: #b32400; display: flex; align-items: center; justify-content: center; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 6px;">
                        <i class="fa fa-calendar-check" style="font-size: 2rem; color: white; margin-right: 10px;"></i>
                           <h6 style="color: #ffffff!important; font-weight: 600; font-size: 1rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Safe Loading</h6>
                   </div>
                   
                        <p style="font-size: 1rem; color: #333; line-height: 1.6;"> Our team handles your parcels with care, ensuring safe and secure loading, minimizing any risk of  damage during the process.  </p>
                        <!-- <a href="about.html" style="display: inline-block;  margin-top: 35px;  color: #e53935;  border-bottom: 2px solid #e53935;  padding-bottom: 2px;  text-decoration: none;  font-weight: 600;  transition: 0.3s ease;">Read More</a> -->
                </div>
                <div class="service-box" style="width: 100%; max-width: 280px; margin: 0 auto 40px; text-align: center; box-shadow: 1px 2px 8px 1px #b32400;   border-radius: 15px; padding:8px">
                   <div class="icon-container" style="background-color: #b32400; display: flex; align-items: center; justify-content: center; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 6px;">
                        <i class="fa fa-calendar-check" style="font-size: 2rem; color: white; margin-right: 10px;"></i>
                           <h6 style="color: #ffffff!important; font-weight: 600; font-size: 1rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Safe Delivery</h6>
                   </div>
                        <p style="font-size: 1rem; color: #333; line-height: 1.6;"> We prioritize the safety of your items throughout the journey, offering reliable and timely  delivery to the desired destination without compromise.  </p>
                        <!-- <a href="about.html" style="display: inline-block;  margin-top: 10px;  color: #e53935;  border-bottom: 2px solid #e53935;  padding-bottom: 2px;  text-decoration: none;  font-weight: 600;  transition: 0.3s ease;">Read More</a> -->
                </div>
                
                
            </div>
        </div>
  </div>
<style>
/* Card Fade-In + Slight Up Animation */
@keyframes cardFadeUp {
  0% {
    opacity: 0;
    transform: translateY(40px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Hover 3D Lift Effect */
.service-box {
  animation: cardFadeUp 0.8s ease forwards;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-box:hover {
  transform: translateY(-12px) scale(1.03);
  box-shadow: 0px 12px 25px rgba(128, 0, 128, 0.4);
}

/* Icon container bounce animation on hover */
.icon-container {
  transition: transform 0.3s ease;
}

.service-box:hover .icon-container {
  transform: scale(1.08);
}

/* Staggered Animation Delay */
.service-box:nth-child(1) {
  animation-delay: 0.1s;
}
.service-box:nth-child(2) {
  animation-delay: 0.25s;
}
.service-box:nth-child(3) {
  animation-delay: 0.4s;
}
.service-box:nth-child(4) {
  animation-delay: 0.55s;
}
</style>


<div style="background-color: #f7e9ff; margin: 40px 0; padding: 40px 30px; animation: fadeIn 1.3s ease-in-out;">
    <div class="container" style="max-width: 1500px; margin: auto;">
        <h5 style="color: #a100ff !important; font-size: 2rem; font-weight: 800; text-align: center; animation: slideDown 1s ease;">
            WHY CHOOSE US?
        </h5>

        <div style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap; margin: 40px 0;">

            <!-- Left Side: Image -->
            <div style="flex: 1; min-width: 300px; text-align: left; animation: slideLeft 1s ease;">
                <img src="https://deliverytech.ca/img/blog/six-reasons-why-you-should-choose-local-courier-services1.png"
                    alt="Truck Image"
                    style="width: 100%; max-width: 700px; height: auto; border-radius: 6px; box-shadow: 0 0 20px rgba(162, 0, 255, 0.3);">
            </div>

            <!-- Right Side: Text -->
            <div style="flex: 1; min-width: 300px; animation: slideRight 1s ease;">
                <h2 style="font-size: 2.8rem; color: #5e0099; font-weight: 800; margin-bottom: 20px; line-height: 1.2;">
                    We Professional Moving Company
                </h2>

                <p style="color: #2a2a2a; font-size: 1.2rem; line-height: 1.8;">
                    From a single box to a full truckload, you can trust BD Epxress, Domestic & International. To pick up
                    and deliver your freight on-time, damage-free and within your budget. We offer expedited ground freight
                    service with efficient routing, speed and savings.
                </p>

                <ul class="custom-list" style="list-style: none; padding: 0; margin-bottom: 20px;">

                    <!-- ICON 1 -->
                    <li style="display: flex; align-items: center; margin-bottom: 12px; font-size: 1.2rem; color: #5e0099; font-weight: 600; animation: zoomIn 0.8s ease;">
                        <svg width="24" height="24" viewBox="0 0 24 24" style="margin-right: 12px;">
                            <circle cx="12" cy="12" r="12" fill="#0CA94E"/>
                            <path d="M6 12.5L10 16.5L18 8.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Save & Secure Move
                    </li>

                    <!-- ICON 2 -->
                    <li style="display: flex; align-items: center; margin-bottom: 12px; font-size: 1.2rem; color: #5e0099; font-weight: 600; animation: zoomIn 1s ease;">
                        <svg width="24" height="24" viewBox="0 0 24 24" style="margin-right: 12px;">
                            <circle cx="12" cy="12" r="12" fill="#0CA94E"/>
                            <path d="M6 12.5L10 16.5L18 8.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Delivery On Time
                    </li>

                    <!-- ICON 3 -->
                    <li style="display: flex; align-items: center; margin-bottom: 12px; font-size: 1.2rem; color: #5e0099; font-weight: 600; animation: zoomIn 1.2s ease;">
                        <svg width="24" height="24" viewBox="0 0 24 24" style="margin-right: 12px;">
                            <circle cx="12" cy="12" r="12" fill="#0CA94E"/>
                            <path d="M6 12.5L10 16.5L18 8.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Moving Is Quick & Easy
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>

<!-- 🔥 INLINE ANIMATIONS CSS -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-40px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideLeft {
    from { opacity: 0; transform: translateX(-40px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes slideRight {
    from { opacity: 0; transform: translateX(40px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes zoomIn {
    from { opacity: 0; transform: scale(0.6); }
    to { opacity: 1; transform: scale(1); }
}
</style>



 <div style="padding: 40px 20px; ">
  <div style="max-width: 1400px; margin: auto;">
    <h5 style="color: rgb(183, 47, 32) !important; font-size: 2rem; font-weight: 800; text-align: center;">TESTIMONIAL</h5>
    <h2 style="text-align: center; font-size: 2.5rem; color: #2c3e50; font-weight: 800; margin-bottom: 40px;">   Our Clients Say </h2>

    <!-- Scroll Container -->
    <div id="testimonial-scroll" style="overflow: hidden; white-space: nowrap; position: relative;">
      <div id="scroll-wrapper" style="display: inline-flex; align-items: stretch; padding:20px">
        
        <!-- Reusable Card Template -->
        <div style="min-width: 350px; max-width: 350px; min-height: 320px; margin-right: 20px; background: #fff; border-radius: 8px; padding: 20px; box-shadow: 1px 2px 8px 1px blue; position: relative; display: flex; flex-direction: column; justify-content: space-between;">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" style="width: 60px; height: 60px; border-radius: 50%; margin: 0 auto 10px auto; border: 2px solid purple; padding:2px;">
                <h4 style="margin: 10px 0 4px; font-size: 1.1rem; color: #2c3e50; text-align: center;">Praveen</h4>
                <span style="color: gray; font-size: 0.9rem; text-align: center;">Marketing Staff</span>
                <p style="font-size: 1rem; color: #555; white-space: normal; word-break: break-word; margin-top: 10px; text-align: center;">  Affordable and efficient. My parcel reached its destination safely and on time. I’ll definitely be using them again!</p>
        </div>

        <div style="min-width: 350px; max-width: 350px; min-height: 320px; margin-right: 20px; background: #fff; border-radius: 8px; padding: 20px; box-shadow: 1px 2px 8px 1px blue;  position: relative; display: flex; flex-direction: column; justify-content: space-between;">
                <img src="https://randomuser.me/api/portraits/men/45.jpg" style="width: 60px; height: 60px; border-radius: 50%; margin: 0 auto 10px auto; border: 2px solid purple; padding:2px;">
                <h4 style="margin: 10px 0 4px; font-size: 1.1rem; color: #2c3e50; text-align: center;">Akash Shrivas</h4> 
                <span style="color: gray; font-size: 0.9rem; text-align: center;">Project Manager</span>
                <p style="font-size: 1rem; color: #555; white-space: normal; word-break: break-word; margin-top: 10px; text-align: center;">  I’ve used Yes Courier multiple times. The pick-up was on time, and the delivery was fast. Highly recommend!</p>
        </div>
           <!-- Testimonial Card: Anand Sharma -->
       <div style="min-width: 350px; max-width: 350px; min-height: 320px; margin-right: 20px; background: #fff; border-radius: 8px; padding: 20px; box-shadow: 1px 2px 8px 1px blue;  position: relative; display: flex; flex-direction: column; justify-content: space-between; align-items: center; text-align: center;">
                <img src="https://randomuser.me/api/portraits/men/56.jpg" style="width: 60px; height: 60px; border-radius: 50%; margin-bottom: 10px; border: 2px solid purple; padding:2px;">
                <h4 style="margin: 10px 0 4px; font-size: 1.1rem; color: #2c3e50;">Anand Sharma</h4>
                <span style="color: gray; font-size: 0.9rem;">IT Supervisor</span>
                <p style="font-size: 1rem; color: #555; white-space: normal; word-break: break-word; margin-top: 10px;">  From start to finish, everything was handled professionally. The team ensured my luggage was safely picked up. </p>
        </div>

          <!-- Testimonial Card: Prince Patidar -->
       <div style="min-width: 350px; max-width: 350px; min-height: 320px; margin-right: 20px; background: #fff; border-radius: 8px; padding: 20px; box-shadow: 2px 4px 10px 1px blue; position: relative; display: flex; flex-direction: column; justify-content: space-between; align-items: center; text-align: center;">
                <img src="https://randomuser.me/api/portraits/men/55.jpg" style="width: 60px; height: 60px; border-radius: 50%; margin-bottom: 10px; border: 2px solid purple; padding:2px;">
                <h4 style="margin: 10px 0 4px; font-size: 1.1rem; color: #2c3e50;">Prince Patidar</h4>
                <span style="color: gray; font-size: 0.9rem;">Designer</span>
                <p style="font-size: 1rem; color: #555; white-space: normal; word-break: break-word; margin-top: 10px;">   Yes Courier has consistently delivered my packages on time. I trust them with all my shipping needs. </p>
       </div>
      </div>
    </div>
  </div>
</div>


<div style=" margin: auto; padding: 0;">
  
  <div class="container" style="max-width: 1500px; margin: auto;">
   <h5 style="color: rgb(183, 47, 32) !important; font-size: 2rem; font-weight: 800; text-align: center;">OUR SERVICES</h5>
    <h2 style="text-align: center; font-size: 2.5rem; color: #2c3e50; font-weight: 800; margin-bottom: 40px;">We Offer Services </h2>

    <div style="display: flex; justify-content: center; gap: 20px; padding: 20px 20px; flex-wrap: wrap;">

<!-- Card 1 -->
<div style="
  width: 100%; max-width: 500px; background: linear-gradient(135deg, #8e24aa15, #d5000015);
  border-radius: 10px; 
  overflow: hidden;
  transition: all 0.3s ease; 
  border: 2px solid blue;
  padding:2px;
 ">
    
    <img src="https://kaizenaire.com/wp-content/uploads/2024/01/16.6-1024x585.jpg" 
         alt="Road Delivery" 
         style="width: 100%; height: auto; display: block; border-radius: 10px;">

    <div style="padding: 20px;">
      <h3 style="margin: 0 0 10px; font-size: 1.3rem; font-weight: 700; color: #8e24aa;">By Road Delivery</h3>
      <p style="font-size: 1rem; color: #444; line-height: 1.6;">
        We offer an economical delivery solution for heavy shipments which requires special arrangements such as big vehicles and material handling equipment.
      </p>
      <a href="#" style="color: #b32400; font-weight: bold; font-size: 0.95rem; text-decoration: none;">READ MORE &rarr;</a>
    </div>
</div>

<!-- Card 2 -->
<div style="
  width: 100%; max-width: 500px; background: linear-gradient(135deg, #7b1fa215, #c5116215);
  border-radius: 10px;
  border: 2px solid blue;
  padding:2px; 
  overflow: hidden;
  transition: all 0.3s ease; 
  "
  >

    <img src="https://th.bing.com/th/id/R.e19d095c1db31681701c9ac8df65d2e9?rik=keyK3aR%2bx8UIwQ&riu=http%3a%2f%2ficslogistics.in%2fwp-content%2fuploads%2f2020%2f02%2fgallerypage_7.jpg&ehk=Pz6tKN90qDnGNOx4IqoYZp0%2f9EgWEcm%2bEDFzym4k0MU%3d&risl=&pid=ImgRaw&r=0" 
         alt="Train Delivery" 
         style="width: 100%; height: 54%; display: block; border-radius: 10px;">

    <div style="padding: 20px;">
      <h3 style="margin: 0 0 10px; font-size: 1.3rem; font-weight: 700; color: #7b1fa2;">By Train Delivery</h3>
      <p style="font-size: 1rem; color: #444; line-height: 1.6;">
        As a cost effective alternative, we provide full surface logistics services throughout India including customer tailored time definite options.
      </p>
      <a href="#" style="color: #b32400; font-weight: bold; font-size: 0.95rem; text-decoration: none;">READ MORE &rarr;</a>
    </div>
</div>

</div>
  </div>
</div>



<script>
  const container = document.getElementById("testimonial-scroll");
  const wrapper = document.getElementById("scroll-wrapper");

  wrapper.innerHTML += wrapper.innerHTML; // Clone content for looping

  let scrollX = 0;
  const scrollSpeed = 1;

  function autoScroll() {
    scrollX += scrollSpeed;
    if (scrollX >= wrapper.scrollWidth / 2) {
      scrollX = 0;
    }
    container.scrollTo({ left: scrollX });
    requestAnimationFrame(autoScroll);
  }

  autoScroll();
</script>

  

<!-- JavaScript: Counter Animation -->
 <script>
  const counters = document.querySelectorAll('.counter');
  const speed = 100; // lower = faster

  counters.forEach(counter => {
    const updateCount = () => {
      const target = +counter.getAttribute('data-target');
      const count = +counter.innerText;

      const increment = Math.ceil(target / speed);

      if (count < target) {
        counter.innerText = count + increment;
        setTimeout(updateCount, 20);
      } else {
        counter.innerText = target;
      }
    };

    updateCount();
  });
</script>

</section>   
<!-- End of Section: Moving Service -->