<!-- Slider
		============================================= -->
<section id="slider" class="slider-element swiper_wrapper" data-autoplay="6000" data-speed="800" data-loop="true" data-grab="true" data-effect="fade" data-arrow="false" style="height: 600px;">

    <div class="swiper-container swiper-parent">
        <div class="swiper-wrapper">
            <div class="swiper-slide dark">
                <div class="container">
                    <div class="slider-caption slider-caption-center">
                        <div>
                            <div class="h5 mb-2 font-secondary">Why Choose {{ $config->brand_name }}?</div>
                            <h2 class="bottommargin-sm text-white">Best OTP & Marketing SMS</h2>
                            <a href="#" class="button bg-white text-dark button-light">Start Now</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide-bg" style="background-image: url({{ asset('assets/frontend/images/slider/slider3.jpg') }});"></div>
            </div>
            <div class="swiper-slide dark">
                <div class="container">
                    <div class="slider-caption slider-caption-center">
                        <div>
                            <div class="h5 mb-2 font-secondary">Powerful Control Panel</div>
                            <h2 class="bottommargin-sm text-white">Ease of use</h2>
                            <a href="#" class="button bg-black text-info button-light">Start Now</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide-bg" style="background-image: url({{ asset('assets/frontend/images/slider/slider2.jpg') }}); background-position: center 20%;"></div>
            </div>
            <div class="swiper-slide dark">
                <div class="container">
                    <div class="slider-caption slider-caption-center">
                        <div>
                            <div class="h5 mb-2 font-secondary">Send SMS by your choice</div>
                            <h2 class="bottommargin-sm text-white">Dynamic SMS, SMS From Excel</h2>
                            <a href="#" class="button bg-white text-dark button-light">Start Now</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide-bg" style="background-image: url({{ asset('assets/frontend/images/slider/slider1.png') }}); background-position: center 40%;"></div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

</section><!-- #Slider End -->
