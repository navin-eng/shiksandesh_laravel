@extends('frontend.layout.master')
@section('frontend-content')

<!-- LightGallery Video Plugin (Required for YouTube/Vimeo) -->
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/video/lg-video.min.js"></script>

<style>
    /* Professional Gallery Theme */
    body {
        background-color: #f8fafc;
    }
    
    .gallery-header {
        text-align: center;
        padding: 60px 20px 40px;
    }

    .gallery-header h1 {
        font-size: 42px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }
    
    .gallery-header p {
        color: #64748b;
        font-size: 18px;
        max-width: 600px;
        margin: 0 auto;
    }

    .gallery-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px 80px;
    }

    .gallery-tabs {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 40px;
    }

    .g-btn {
        padding: 12px 28px;
        border: none;
        border-radius: 50px;
        background: #fff;
        color: #475569;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        font-size: 16px;
    }

    .g-btn:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .g-btn.active {
        background: var(--brand-color, #2563eb);
        color: white;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
    }

    /* Albums Grid - Folder Concept */
    .albums-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    .album-card {
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        cursor: pointer;
        transition: all 0.4s ease;
        position: relative;
    }

    .album-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .album-img-wrap {
        position: relative;
        height: 240px;
        overflow: hidden;
    }

    .album-img-wrap::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
        z-index: 1;
    }

    .album-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }

    .album-card:hover .album-img-wrap img {
        transform: scale(1.1);
    }

    .album-info {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 20px;
        z-index: 2;
        color: white;
    }

    .album-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 4px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .album-count {
        font-size: 14px;
        font-weight: 500;
        opacity: 0.9;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: none;
        color: #0f172a;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 50px;
        cursor: pointer;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        transition: 0.3s;
    }
    .back-btn:hover {
        background: #f1f5f9;
        transform: translateX(-5px);
    }

    /* Masonry Layout */
    .masonry-layout {
        column-count: 4;
        column-gap: 20px;
    }

    @media (max-width: 1200px) { .masonry-layout { column-count: 3; } }
    @media (max-width: 991px) { .masonry-layout { column-count: 2; } }
    @media (max-width: 575px) { .masonry-layout { column-count: 1; } }

    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        margin-bottom: 20px;
        display: block;
        background: #e2e8f0;
        break-inside: avoid;
    }

    .gallery-item img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }

    .gallery-item::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .gallery-item:hover::before {
        opacity: 1;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }
    
    .item-overlay {
        position: absolute;
        bottom: -30px;
        left: 0;
        width: 100%;
        padding: 15px;
        color: white;
        z-index: 2;
        transition: 0.3s ease;
        opacity: 0;
    }
    
    .gallery-item:hover .item-overlay {
        bottom: 0;
        opacity: 1;
    }

    .play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 60px;
        color: white;
        opacity: 0.8;
        pointer-events: none;
        z-index: 2;
        transition: 0.3s;
    }

    .gallery-item:hover .play-icon {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.1);
    }
    
    /* Grid Layout Alternate */
    .grid-layout {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .grid-layout .gallery-item {
        margin-bottom: 0;
        height: 250px;
    }
    
    .grid-layout .gallery-item img {
        height: 100%;
        object-fit: cover;
    }
    
    .layout-switcher {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-bottom: 20px;
    }
    
    .layout-btn {
        background: #e2e8f0;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        color: #475569;
        cursor: pointer;
        transition: 0.3s;
    }
    
    .layout-btn:hover {
        background: #cbd5e1;
    }
    
    .layout-btn.active {
        background: var(--brand-color, #2563eb);
        color: white;
    }
</style>

<div class="gallery-header" data-aos="fade-up">
    <h1>Our Gallery</h1>
    <p>Explore our campus life, events, and memories through these visual stories.</p>
</div>

<div class="gallery-container">
    
    <div class="gallery-tabs" data-aos="fade-up" data-aos-delay="100">
        <button class="g-btn active" id="btn-tab-albums" onclick="showTab('albums')">Albums</button>
        <button class="g-btn" id="btn-tab-all" onclick="showTab('all')">All Photos & Videos</button>
    </div>

    <!-- Albums View -->
    <div id="view-albums">
        <div class="albums-grid">
            @foreach($albums as $album)
                @if($album->status === 'active')
                    @php
                        $photoCount = \App\Models\Gallery::where('album_id', $album->id)->count();
                    @endphp
                    <div class="album-card" onclick="openAlbum({{ $album->id }}, '{{ addslashes($album->name) }}')" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="album-img-wrap">
                            @if($album->cover_image)
                                <img src="{{ asset('backend/images/gallery/'.$album->cover_image) }}" alt="{{ $album->name }}">
                            @else
                                @php
                                    $latestImage = \App\Models\Gallery::where('album_id', $album->id)->where('type', 'image')->whereNotNull('file_path')->latest()->first();
                                @endphp
                                @if($latestImage)
                                    <img src="{{ asset('backend/images/gallery/'.$latestImage->file_path) }}" alt="{{ $album->name }}">
                                @else
                                    <div style="width:100%;height:100%;background:#cbd5e1;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:60px;"><i class="bi bi-images"></i></div>
                                @endif
                            @endif
                        </div>
                        <div class="album-info">
                            <h3 class="album-title">{{ $album->name }}</h3>
                            <div class="album-count">{{ $photoCount }} Media Items</div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- All Items / Single Album View -->
    <div id="view-all" style="display: none;">
        
        <div id="album-header" style="display:none;">
            <button class="back-btn" onclick="showTab('albums')"><i class="bi bi-arrow-left"></i> Back to Albums</button>
            <h2 id="current-album-name" style="font-size: 32px; font-weight:800; margin-bottom: 20px;"></h2>
        </div>
        
        <div class="layout-switcher">
            <button class="layout-btn active" id="btn-masonry" onclick="switchLayout('masonry')" title="Masonry Layout"><i class="bi bi-columns-gap"></i></button>
            <button class="layout-btn" id="btn-grid" onclick="switchLayout('grid')" title="Grid Layout"><i class="bi bi-grid-fill"></i></button>
        </div>

        <div id="gallery-wrapper" class="masonry-layout">
            @foreach($gallery as $item)
                @php
                    if($item->type === 'image') {
                        $src = asset('backend/images/gallery/'.$item->file_path);
                        $thumb = $src;
                    } elseif($item->type === 'image_url') {
                        $src = $item->url;
                        $thumb = $item->url;
                    } else { // video_url
                        $src = $item->url;
                        if (str_contains($src, 'youtube') || str_contains($src, 'youtu.be')) {
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $src, $match);
                            $thumb = isset($match[1]) ? "https://img.youtube.com/vi/{$match[1]}/maxresdefault.jpg" : "";
                        } else {
                            $thumb = ""; 
                        }
                    }
                @endphp
                <a href="{{ $src }}" class="gallery-item album-item-{{ $item->album_id ?? 'none' }}" data-sub-html="{{ $item->caption }}">
                    @if($item->type === 'video_url')
                        <i class="bi bi-play-circle-fill play-icon"></i>
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="Video Thumbnail">
                        @else
                            <div style="width:100%;height:300px;background:#1a1a2e;"></div>
                        @endif
                    @else
                        <img src="{{ $thumb }}" alt="Gallery Photo">
                    @endif
                    
                    @if($item->caption)
                    <div class="item-overlay">
                        <p style="margin:0; font-size:14px; font-weight:500;">{{ $item->caption }}</p>
                    </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
    let lgInstance = null;

    function initLightGallery() {
        const galleryEl = document.getElementById('gallery-wrapper');
        if (galleryEl && typeof lightGallery !== 'undefined') {
            if(lgInstance) {
                lgInstance.destroy(true);
            }
            // Only select visible items for the gallery
            lgInstance = lightGallery(galleryEl, {
                plugins: [lgVideo],
                speed: 500,
                download: false,
                selector: '.gallery-item[style*="display: block"]' 
            });
        }
    }

    function showTab(tab) {
        document.querySelectorAll('.g-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('view-albums').style.display = 'none';
        document.getElementById('view-all').style.display = 'none';
        document.getElementById('album-header').style.display = 'none';
        
        if (tab === 'albums') {
            document.getElementById('btn-tab-albums').classList.add('active');
            document.getElementById('view-albums').style.display = 'block';
        } else {
            document.getElementById('btn-tab-all').classList.add('active');
            document.getElementById('view-all').style.display = 'block';
            
            // Show all items
            document.querySelectorAll('.gallery-item').forEach(el => {
                el.style.display = 'block';
            });
            initLightGallery();
        }
    }

    function openAlbum(albumId, albumName) {
        document.querySelectorAll('.g-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('view-albums').style.display = 'none';
        document.getElementById('view-all').style.display = 'block';
        
        document.getElementById('album-header').style.display = 'block';
        document.getElementById('current-album-name').innerText = albumName;
        
        document.querySelectorAll('.gallery-item').forEach(el => {
            if(el.classList.contains('album-item-' + albumId)) {
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        });
        
        initLightGallery();
    }
    
    function switchLayout(layout) {
        const wrapper = document.getElementById('gallery-wrapper');
        const btnMasonry = document.getElementById('btn-masonry');
        const btnGrid = document.getElementById('btn-grid');
        
        if (layout === 'grid') {
            wrapper.classList.remove('masonry-layout');
            wrapper.classList.add('grid-layout');
            btnGrid.classList.add('active');
            btnMasonry.classList.remove('active');
        } else {
            wrapper.classList.remove('grid-layout');
            wrapper.classList.add('masonry-layout');
            btnMasonry.classList.add('active');
            btnGrid.classList.remove('active');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        initLightGallery();
    });
</script>

@endsection
