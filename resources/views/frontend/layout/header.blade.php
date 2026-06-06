@php
    $siteSettings = \App\Models\SiteSetting::current();
    $navCourses = \App\Models\Course::where('status', 1)->get();
@endphp
{{-- ===== TOP BAR ===== --}}
@if($siteSettings->show_topbar ?? true)
<div class="gplc-topbar">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="topbar-info d-flex align-items-center gap-3">
            <span><i class="fas fa-phone-alt"></i> {{ $siteSettings->contact_phone }}</span>
            <span class="d-none d-md-flex"><i class="fas fa-envelope"></i> {{ $siteSettings->contact_email }}</span>
            <span class="d-none d-lg-flex"><i class="fas fa-map-marker-alt"></i> {{ $siteSettings->contact_address }}</span>
        </div>
        <div class="topbar-actions d-flex align-items-center gap-2">
            <div class="topbar-social d-flex gap-1">
                <a href="{{ $siteSettings->facebook_url }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ $siteSettings->youtube_url }}" target="_blank"><i class="fab fa-youtube"></i></a>
                <a href="https://wa.me/{{ $siteSettings->whatsapp_number }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
            </div>
            @if(!empty($siteSettings->student_portal_text) && !empty($siteSettings->student_portal_url))
                <a href="{{ $siteSettings->student_portal_url }}" class="portal-btn ms-2" target="_blank">
                    <i class="fas fa-sign-in-alt"></i> {{ $siteSettings->student_portal_text }}
                </a>
            @endif
            @if(!empty($siteSettings->header_button_text) && !empty($siteSettings->header_button_url))
                <a href="{{ $siteSettings->header_button_url }}" class="portal-btn portal-btn-secondary" target="_blank">
                    <i class="fas fa-arrow-up-right-from-square"></i> {{ $siteSettings->header_button_text }}
                </a>
            @endif
        </div>
    </div>
</div>
@endif

{{-- ===== MAIN HEADER ===== --}}
<header class="gplc-header" id="gplcHeader">
    <div class="container">
        <div class="header-inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="gplc-logo">
                <img src="{{ asset('backend/images/logo.png') }}" alt="GPLC Logo">
                <div class="gplc-logo-text">
                    <span class="college-name">{{ $siteSettings->site_name }}</span>
                    <span class="affiliation">{{ $siteSettings->site_tagline }}</span>
                </div>
            </a>

            {{-- Navigation --}}
            <nav class="gplc-nav" id="gplcNav">
                <ul>
                    <li>
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('about.us') }}" class="{{ request()->routeIs('about.us') ? 'active' : '' }}">About Us</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="{{ request()->segment(1)=='course' ? 'active' : '' }}">
                            Academics <i class="fas fa-chevron-down" style="font-size:9px;margin-left:3px;"></i>
                        </a>
                        <ul class="dropdown-menu-gplc">
                            @forelse($navCourses as $nc)
                                <li>
                                    <a href="{{ url('course/' . $nc->slug) }}">
                                        <i class="fas fa-graduation-cap"></i> {{ $nc->name }}
                                    </a>
                                </li>
                            @empty
                                <li><a href="#">No courses yet</a></li>
                            @endforelse
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('member') }}" class="{{ request()->routeIs('member') ? 'active' : '' }}">Faculties</a>
                    </li>
                    <li>
                        <a href="{{ route('calendar') }}" class="{{ request()->routeIs('calendar') ? 'active' : '' }}">Calendar</a>
                    </li>
                    <li>
                        <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="nav-apply">
                            <i class="fas fa-paper-plane"></i> Apply Now
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- Hamburger --}}
            <button class="gplc-hamburger" id="gplcHam" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
