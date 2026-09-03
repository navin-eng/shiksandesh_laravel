@extends('frontend.layout.master')
@section('frontend-content')
@php
    $data = App\Models\AboutUs::first();
    $faqs = App\Models\AboutUsFaq::where('status', 1)->orderBy('sort_order')->get();
@endphp

{{-- ===== PAGE HERO ===== --}}
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>About Us</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                About Us
            </nav>
        </div>
    </div>
</div>

{{-- ===== ABOUT CONTENT ===== --}}
<section class="section-block">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4" data-aos="fade-up">
                <div class="contact-info-card h-100">
                    <h4>Why Students Visit This Page</h4>
                    <p class="text-muted">We designed this section to help students and parents quickly understand the college, academic environment, support, and next steps.</p>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-circle-check"></i></div>
                        <div class="info-text"><div class="label">Admissions Help</div>Key answers before applying.</div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-book-open"></i></div>
                        <div class="info-text"><div class="label">Academic Clarity</div>Programs, affiliation, and learning environment.</div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-comments"></i></div>
                        <div class="info-text"><div class="label">Student FAQs</div>Common questions answered in one place.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="80">
                <div class="about-content h-100">
                    <div class="rich">
                        <h3>About {{ $siteSettings->site_name ?? 'Shiksha Sandesh English School' }}</h3>
                        <p>{{ $siteSettings->site_name ?? 'Shiksha Sandesh English School' }} is committed to providing an inspiring learning environment where students excel academically, develop strong moral values, and grow into confident, capable global citizens.</p>
                        <p>With an emphasis on holistic education, dedicated teachers, and modern facilities, we partner with parents to help every student discover their potential and achieve academic success.</p>
                    </div>
                </div>
            </div>
        </div>

        @if($data)
            <div class="about-content" data-aos="fade-up">
                <div class="rich">
                    {!! $data->desc !!}
                </div>
            </div>
        @else
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fa-solid fa-school fa-3x mb-3" style="color: var(--primary);"></i>
                <h4 style="color: var(--dark);">Shaping Tomorrow's Leaders</h4>
                <p class="text-muted">Established with a vision for academic excellence, character building, and community empowerment.</p>
            </div>
        @endif

        {{-- ===== SCHOOL HERITAGE & AFFILIATION BOX ===== --}}
        <div class="mt-5" data-aos="fade-up" data-aos-delay="100">
            <div style="background: #f8fafc; border: 1.5px solid var(--border); border-radius: var(--radius-md); padding: 40px 36px; display: flex; gap: 32px; align-items: center; flex-wrap: wrap;">
                <div style="flex-shrink: 0; text-align: center;">
                    <div style="width: 90px; height: 90px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <i class="fa-solid fa-graduation-cap fa-2x" style="color: #fff;"></i>
                    </div>
                    <span style="font-size: 11px; font-weight: 700; color: var(--primary); letter-spacing: 1.5px; text-transform: uppercase;">Heritage</span>
                </div>
                <div style="flex: 1; min-width: 220px;">
                    <span class="section-tag" style="margin-bottom: 10px;">Our Journey</span>
                    <h3 style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 12px;">
                        Excellence in Education Since 1993 A.D. (2050 B.S.)
                    </h3>
                    <p style="font-size: 14.5px; color: var(--text-muted); line-height: 1.75; margin: 0;">
                        {{ $siteSettings->site_name ?? 'Shiksha Sandesh English School' }} is proudly affiliated with the
                        <strong style="color: var(--primary);">National Examination Board (NEB) Nepal</strong>.
                        Situated in Belbari-2, Morang, our institution provides well-rounded, value-based education from early childhood through secondary levels. 
                        With experienced faculty, modern science and computer laboratories, a resourceful library, and enriching extracurricular activities, we empower each student to realize their fullest potential.
                    </p>
                    <div style="margin-top: 18px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: var(--primary); color: #fff; font-size: 11.5px; font-weight: 700; padding: 5px 16px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-check-circle"></i> Affiliated with NEB Nepal &bull; Estd. 2050 B.S.
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag">Student FAQs</span>
                <h2 class="section-title mt-2">Questions Students Commonly Ask</h2>
                <div class="section-divider center"></div>
                <p class="text-muted mt-3 mb-0">Add and manage these answers from the dashboard so students can get important information quickly.</p>
            </div>

            @if($faqs->count())
                <div class="accordion" id="aboutFaqAccordion">
                    @foreach($faqs as $faq)
                        <div class="accordion-item mb-3 border-0 shadow-sm" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <h2 class="accordion-header" id="faq-heading-{{ $faq->id }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faq-collapse-{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq-collapse-{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="faq-heading-{{ $faq->id }}" data-bs-parent="#aboutFaqAccordion">
                                <div class="accordion-body">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 border rounded-4 bg-light" data-aos="fade-up">
                    <p class="mb-0 text-muted">No FAQs have been added yet. You can manage them from the About Us page in the dashboard.</p>
                </div>
            @endif
        </div>

    </div>
</section>

    @include('frontend.layout.sections', ['page' => 'aboutus'])
@endsection
