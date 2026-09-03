<div class="gplc-hero">
    <div class="swiper MainSwiper">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
                <div class="swiper-slide gplc-hero-slide">
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->title2 }}">
                    <div class="gplc-hero-overlay"></div>
                    <div class="gplc-hero-content">
                        <span class="tag-line">
                            <i class="fa-solid fa-graduation-cap"></i>
                            {{ $siteSettings->site_tagline ?? 'Excellence in Education Since 1993' }}
                        </span>
                        <h1>{{ $banner->title2 }}</h1>
                        <p>Nurturing Young Minds & Inspiring Tomorrow's Leaders</p>
                        <div class="gplc-hero-actions">
                            <a href="{{ route('about.us') }}" class="btn-gplc">
                                <i class="fa-solid fa-compass"></i> Explore School
                            </a>
                            <a href="{{ route('contact') }}" class="btn-gplc-light">
                                <i class="fa-solid fa-phone"></i> Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide gplc-hero-slide" style="background: linear-gradient(135deg, var(--dark) 0%, var(--primary-dark, #0e2d54) 100%);">
                    <div class="gplc-hero-overlay"></div>
                    <div class="gplc-hero-content">
                        <span class="tag-line">
                            <i class="fa-solid fa-graduation-cap"></i>
                            {{ $siteSettings->site_tagline ?? 'Excellence in Education Since 1993' }}
                        </span>
                        <h1>{{ $siteSettings->site_name ?? 'Shiksha Sandesh English School' }}</h1>
                        <p>Nurturing Young Minds & Inspiring Tomorrow's Leaders</p>
                        <div class="gplc-hero-actions">
                            <a href="{{ route('about.us') }}" class="btn-gplc">
                                <i class="fa-solid fa-compass"></i> Explore School
                            </a>
                            <a href="{{ route('contact') }}" class="btn-gplc-light">
                                <i class="fa-solid fa-phone"></i> Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="hero-pagination swiper-pagination"></div>
    </div>
</div>
