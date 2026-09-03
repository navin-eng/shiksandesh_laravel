@extends('backend.pages.layout.master')
@push('b-title', 'Site Settings')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Site Settings</h3>
            <p class="text-muted mb-0">Control theme colors, logo text, contact information, and gallery display style.</p>
        </div>
    </div>

    <form action="{{ route('site.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Brand Identity</h5>
                        <div class="mb-3">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Name</label>
                            <input type="text" name="site_short_name" class="form-control" value="{{ old('site_short_name', $settings->site_short_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tagline / Logo Subtitle</label>
                            <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings->site_tagline) }}" required>
                        </div>

                        <hr class="my-3 text-muted">

                        {{-- Site Logo --}}
                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Site Logo</span>
                                @if($settings->site_logo)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Custom Logo Active</span>
                                @else
                                    <span class="badge bg-light text-muted border">Default Logo</span>
                                @endif
                            </label>
                            <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light mb-2">
                                <div class="bg-white border rounded p-2 d-flex align-items-center justify-content-center" style="min-width: 90px; height: 60px;">
                                    <img src="{{ $settings->site_logo ? asset($settings->site_logo) : asset('backend/images/logo.png') }}" 
                                         alt="Current Logo" 
                                         id="logoPreview"
                                         style="max-height: 44px; max-width: 80px; object-fit: contain;">
                                </div>
                                <div class="small text-muted flex-grow-1">
                                    Recommended: PNG or SVG with transparent background (Max 2MB).
                                </div>
                            </div>
                            <input type="file" name="site_logo" id="siteLogoInput" class="form-control" accept="image/*">
                            @if($settings->site_logo)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="removeLogoCheck">
                                    <label class="form-check-label text-danger small" for="removeLogoCheck">
                                        Reset to default logo
                                    </label>
                                </div>
                            @endif
                        </div>

                        {{-- Site Favicon --}}
                        <div class="mb-0">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Browser Favicon</span>
                                @if($settings->site_favicon)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Custom Favicon</span>
                                @else
                                    <span class="badge bg-light text-muted border">Default Favicon</span>
                                @endif
                            </label>
                            <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light mb-2">
                                <div class="bg-white border rounded p-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <img src="{{ $settings->site_favicon ? asset($settings->site_favicon) : asset('backend/images/favicon.ico') }}" 
                                         alt="Current Favicon" 
                                         id="faviconPreview"
                                         style="width: 32px; height: 32px; object-fit: contain;">
                                </div>
                                <div class="small text-muted flex-grow-1">
                                    Recommended: 32x32px or 64x64px ICO or PNG.
                                </div>
                            </div>
                            <input type="file" name="site_favicon" id="siteFaviconInput" class="form-control" accept=".ico,.png,.jpg,.svg,.webp">
                            @if($settings->site_favicon)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_favicon" value="1" id="removeFaviconCheck">
                                    <label class="form-check-label text-danger small" for="removeFaviconCheck">
                                        Reset to default favicon
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Theme Colors</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Primary</label>
                                <input type="color" name="primary_color" class="form-control form-control-color w-100" value="{{ old('primary_color', $settings->primary_color) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Primary Dark</label>
                                <input type="color" name="primary_dark" class="form-control form-control-color w-100" value="{{ old('primary_dark', $settings->primary_dark) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Primary Light</label>
                                <input type="color" name="primary_light" class="form-control form-control-color w-100" value="{{ old('primary_light', $settings->primary_light) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Accent</label>
                                <input type="color" name="accent_color" class="form-control form-control-color w-100" value="{{ old('accent_color', $settings->accent_color) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Contact & Social</h5>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings->contact_phone) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings->contact_email) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="contact_address" class="form-control" value="{{ old('contact_address', $settings->contact_address) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Facebook URL</label>
                            <input type="text" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings->facebook_url) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">YouTube URL</label>
                            <input type="text" name="youtube_url" class="form-control" value="{{ old('youtube_url', $settings->youtube_url) }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Instagram URL</label>
                            <input type="text" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings->instagram_url) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Gallery Template</h5>
                        <div class="mb-3">
                            <label class="form-label">Layout Style</label>
                            <select name="gallery_layout" class="form-control">
                                <option value="masonry" {{ old('gallery_layout', $settings->gallery_layout) === 'masonry' ? 'selected' : '' }}>Masonry Grid</option>
                                <option value="spotlight" {{ old('gallery_layout', $settings->gallery_layout) === 'spotlight' ? 'selected' : '' }}>Spotlight Cards</option>
                                <option value="storyboard" {{ old('gallery_layout', $settings->gallery_layout) === 'storyboard' ? 'selected' : '' }}>Storyboard Timeline</option>
                            </select>
                        </div>
                        <p class="text-muted mb-0">All templates are mobile-friendly. Change this anytime to switch how gallery photos appear to visitors.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Quick Access Buttons</h5>
                        <div class="mb-3">
                            <label class="form-label">Student Portal Text</label>
                            <input type="text" name="student_portal_text" class="form-control" value="{{ old('student_portal_text', $settings->student_portal_text) }}" placeholder="e.g. Student Portal">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Student Portal Link</label>
                            <input type="text" name="student_portal_url" class="form-control" value="{{ old('student_portal_url', $settings->student_portal_url) }}" placeholder="https://example.com/login">
                        </div>
                        <hr>
                        <h6 class="mb-3">Extra Header Button</h6>
                        <div class="mb-3">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="header_button_text" class="form-control" value="{{ old('header_button_text', $settings->header_button_text) }}" placeholder="e.g. Apply Online">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Button Link</label>
                            <input type="text" name="header_button_url" class="form-control" value="{{ old('header_button_url', $settings->header_button_url) }}" placeholder="https://example.com/apply">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Sticky Notice Widget</h5>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="showStickyNotice" name="show_sticky_notice" value="1" {{ old('show_sticky_notice', $settings->show_sticky_notice) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showStickyNotice">Show sticky notice on public pages</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Widget Title</label>
                            <input type="text" name="sticky_notice_title" class="form-control" value="{{ old('sticky_notice_title', $settings->sticky_notice_title) }}" placeholder="e.g. Latest Notices">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">How Many Notices To Show</label>
                            <input type="number" min="1" max="10" name="sticky_notice_limit" class="form-control" value="{{ old('sticky_notice_limit', $settings->sticky_notice_limit ?? 5) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Public Display Controls</h5>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">System Calendar Format</label>
                            <select name="calendar_format" class="form-select border-primary bg-primary-subtle">
                                <option value="ad" {{ old('calendar_format', $settings->calendar_format ?? 'ad') === 'ad' ? 'selected' : '' }}>English (A.D.)</option>
                                <option value="bs" {{ old('calendar_format', $settings->calendar_format ?? 'ad') === 'bs' ? 'selected' : '' }}>Nepali (B.S.)</option>
                            </select>
                            <small class="text-muted d-block mt-1">This will change how dates are displayed across the entire public website.</small>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="showTopbar" name="show_topbar" value="1" {{ old('show_topbar', $settings->show_topbar ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showTopbar">Show top information bar</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="showWhatsappButton" name="show_whatsapp_button" value="1" {{ old('show_whatsapp_button', $settings->show_whatsapp_button ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showWhatsappButton">Show WhatsApp floating button</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="showBackToTop" name="show_back_to_top" value="1" {{ old('show_back_to_top', $settings->show_back_to_top ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showBackToTop">Show back-to-top button</label>
                        </div>
                        <hr>
                        <h6 class="mb-3">Sticky Notice Behavior</h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="stickyDesktopCollapsed" name="sticky_notice_desktop_collapsed" value="1" {{ old('sticky_notice_desktop_collapsed', $settings->sticky_notice_desktop_collapsed ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="stickyDesktopCollapsed">Start collapsed on desktop</label>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="stickyMobileCollapsed" name="sticky_notice_mobile_collapsed" value="1" {{ old('sticky_notice_mobile_collapsed', $settings->sticky_notice_mobile_collapsed ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="stickyMobileCollapsed">Start collapsed on mobile</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Activity Log</h5>
                        <p class="text-muted mb-0">Recent changes made to the site settings.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>When</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                    <td>{{ $log->user_name }}</td>
                                    <td><span class="badge bg-primary">{{ ucfirst($log->action) }}</span></td>
                                    <td>{{ $log->summary }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No settings activity logged yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-4">Save Site Settings</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoInput = document.getElementById('siteLogoInput');
        const logoPreview = document.getElementById('logoPreview');
        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        logoPreview.src = ev.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        const favInput = document.getElementById('siteFaviconInput');
        const favPreview = document.getElementById('faviconPreview');
        if (favInput && favPreview) {
            favInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        favPreview.src = ev.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
    });
</script>
@endpush
