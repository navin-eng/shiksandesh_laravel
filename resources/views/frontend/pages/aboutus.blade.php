@extends('frontend.layout.master')
@section('frontend-content')
@php
    $data = App\Models\AboutUs::first();
    $faqs = App\Models\AboutUsFaq::where('status', 1)->orderBy('sort_order')->get();
@endphp

{{-- ===== NEW PAGE HERO ===== --}}
<div style="position: relative; padding: 120px 0; background: linear-gradient(rgba(30, 27, 75, 0.8), rgba(49, 46, 129, 0.9)), url('{{ asset('frontend/images/about_hero.jpg') }}') center/cover no-repeat; color: white;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center" data-aos="fade-up">
            <span style="font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #fbbf24; margin-bottom: 15px; display: block;">Our Story of Excellence</span>
            <h1 style="font-family: var(--font-heading); font-weight: 900; font-size: 3.5rem; margin-bottom: 20px;">Welcome to {{ $siteSettings->site_name ?? 'Shiksha Sandesh' }}</h1>
            <p style="font-size: 1.15rem; max-width: 700px; margin: 0 auto; color: #e0e7ff; line-height: 1.6;">Shaping tomorrow's leaders through quality education, holistic development, and unwavering dedication since 1993.</p>
        </div>
    </div>
</div>

{{-- ===== THE JOURNEY / HERITAGE ===== --}}
<section class="section-block" style="padding: 80px 0; background: var(--bg-body);">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div style="position: relative; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                    <img src="{{ asset('frontend/images/about_hero.jpg') }}" alt="School Building" class="img-fluid" style="width: 100%; height: auto; display: block; object-fit: cover; aspect-ratio: 4/3;">
                    <div style="position: absolute; bottom: 20px; left: 20px; background: rgba(255,255,255,0.95); padding: 15px 25px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
                        <div style="font-size: 1.8rem; font-weight: 900; color: var(--primary); font-family: var(--font-heading); line-height: 1;">30+</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-main); text-transform: uppercase; letter-spacing: 1px;">Years of Legacy</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-tag mb-3">Our Heritage</span>
                <h2 style="font-family: var(--font-heading); font-weight: 800; font-size: 2.5rem; color: var(--dark); margin-bottom: 25px; line-height: 1.2;">Excellence in Education Since 1993 A.D.</h2>
                <div style="font-size: 16px; color: var(--text-muted); line-height: 1.8; margin-bottom: 30px;">
                    <p class="mb-4">Situated in the heart of Belbari-2, Morang, {{ $siteSettings->site_name ?? 'Shiksha Sandesh English School' }} is a premier educational institution affiliated with the <strong>National Examination Board (NEB) Nepal</strong>.</p>
                    <p>What started in 2050 B.S. with a small vision has now blossomed into a massive 12-acre campus, empowering thousands of students to realize their fullest potential through well-rounded, value-based education from early childhood through secondary levels.</p>
                </div>
                <div class="d-flex gap-4">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(var(--bs-primary-rgb), 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--dark);">Prime Location</div>
                            <div style="font-size: 14px; color: var(--text-muted);">Belbari-2, Lalbhitti</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(var(--bs-primary-rgb), 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa-solid fa-tree"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--dark);">12 Acres</div>
                            <div style="font-size: 14px; color: var(--text-muted);">Lush Green Campus</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRINCIPAL'S DESK ===== --}}
<section class="section-block" style="padding: 80px 0; background: #fff;">
    <div class="container">
        <div class="row g-0" style="border-radius: 24px; overflow: hidden; box-shadow: 0 15px 50px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
            <div class="col-lg-5" data-aos="fade-right">
                <img src="{{ asset('frontend/images/about_principal.jpg') }}" alt="Principal" style="width: 100%; height: 100%; min-height: 400px; object-fit: cover;">
            </div>
            <div class="col-lg-7" data-aos="fade-left" style="background: #fff; padding: 60px 50px;">
                <div style="color: var(--primary); font-size: 40px; margin-bottom: 20px; opacity: 0.2;"><i class="fa-solid fa-quote-left"></i></div>
                <h3 style="font-family: var(--font-heading); font-weight: 800; color: var(--dark); margin-bottom: 25px;">Message from the Principal</h3>
                
                @if($data && trim(strip_tags($data->desc)) !== '')
                    <div style="font-size: 16px; color: var(--text-muted); line-height: 1.8; margin-bottom: 30px;" class="rich">
                        {!! $data->desc !!}
                    </div>
                @else
                    <p style="font-size: 16px; color: var(--text-muted); line-height: 1.8; margin-bottom: 30px;">
                        "Welcome to {{ $siteSettings->site_name ?? 'Shiksha Sandesh' }}. Our core philosophy revolves around nurturing not just the academic intellect of our students, but their emotional, social, and moral growth as well. We strive to create an inclusive environment where curiosity is celebrated and innovation is encouraged. Together with our dedicated faculty, we are shaping the future leaders of our nation."
                    </p>
                @endif
                
                <div>
                    <h5 style="font-weight: 700; color: var(--dark); margin-bottom: 5px;">Campus Chief</h5>
                    <span style="color: var(--primary); font-size: 14px; font-weight: 600;">{{ $siteSettings->site_name ?? 'Shiksha Sandesh' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FACILITIES & INFRASTRUCTURE ===== --}}
<section class="section-block" style="padding: 80px 0; background: var(--bg-body);">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Infrastructure</span>
            <h2 class="section-title mt-2">World-Class Facilities</h2>
            <div class="section-divider center"></div>
            <p class="text-muted mt-3 mb-0" style="max-width: 600px; margin: 0 auto;">Providing students with the best resources and environments to thrive academically and personally.</p>
        </div>
        
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-7" data-aos="zoom-in">
                <div style="border-radius: 20px; overflow: hidden; position: relative; height: 100%; min-height: 350px;">
                    <img src="{{ asset('frontend/images/about_campus.jpg') }}" alt="Library" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top:0; left:0;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 40px 30px 30px;">
                        <h3 style="color: white; font-weight: 700; margin-bottom: 10px;">Modern Resource Library</h3>
                        <p style="color: rgba(255,255,255,0.8); margin: 0; font-size: 15px;">A well-stocked haven of knowledge to encourage self-study and research.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-flex flex-column gap-4">
                <div data-aos="fade-up" data-aos-delay="100" style="background: #fff; border-radius: 20px; padding: 35px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); flex: 1;">
                    <div style="width: 60px; height: 60px; border-radius: 15px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">
                        <i class="fa-solid fa-flask"></i>
                    </div>
                    <h4 style="font-weight: 700; color: var(--dark); margin-bottom: 15px;">Science & IT Labs</h4>
                    <p style="color: var(--text-muted); font-size: 15px; margin: 0;">Fully equipped laboratories for practical, hands-on learning in science and computer technology.</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200" style="background: #fff; border-radius: 20px; padding: 35px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); flex: 1;">
                    <div style="width: 60px; height: 60px; border-radius: 15px; background: rgba(234, 179, 8, 0.1); color: #eab308; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">
                        <i class="fa-solid fa-bus"></i>
                    </div>
                    <h4 style="font-weight: 700; color: var(--dark); margin-bottom: 15px;">Transportation Services</h4>
                    <p style="color: var(--text-muted); font-size: 15px; margin: 0;">Safe, reliable, and widespread transport network covering major routes for student convenience.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQS ===== --}}
<section class="section-block" style="padding: 80px 0; background: #fff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5" data-aos="fade-up">
                    <span class="section-tag">Student FAQs</span>
                    <h2 class="section-title mt-2">Questions Students Commonly Ask</h2>
                    <div class="section-divider center"></div>
                </div>

                @if($faqs->count())
                    <div class="accordion custom-accordion" id="aboutFaqAccordion">
                        @foreach($faqs as $faq)
                            <div class="accordion-item mb-4 border-0" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}" style="background: transparent;">
                                <h2 class="accordion-header" id="faq-heading-{{ $faq->id }}">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faq-collapse-{{ $faq->id }}" style="background: #f8fafc; border-radius: 12px; font-weight: 600; color: var(--dark); padding: 20px 25px; box-shadow: none; border: 1px solid #e2e8f0;">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="faq-collapse-{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="faq-heading-{{ $faq->id }}" data-bs-parent="#aboutFaqAccordion">
                                    <div class="accordion-body" style="padding: 20px 25px; color: var(--text-muted); line-height: 1.7; font-size: 15.5px;">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 border rounded-4 bg-light" data-aos="fade-up">
                        <p class="mb-0 text-muted">No FAQs have been added yet. Management can add them from the dashboard.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@include('frontend.layout.sections', ['page' => 'aboutus'])
@endsection
