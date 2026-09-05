@extends('frontend.layout.master')

@section('content')

@push('b-title') Gallery @endpush
@push('b-name') Gallery @endpush
@include('frontend.components.breadcrumb')

<!-- LightGallery Video Plugin (Required for YouTube/Vimeo) -->
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/video/lg-video.min.js"></script>

<style>
    .gallery-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .gallery-tabs {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .g-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 30px;
        background: #f1f5f9;
        color: #334155;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .g-btn.active {
        background: var(--brand-color);
        color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Albums Grid */
    .albums-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .album-card {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .album-card:hover {
        transform: translateY(-5px);
    }

    .album-img-wrap {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .album-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .album-card:hover .album-img-wrap img {
        transform: scale(1.05);
    }

    .album-info {
        padding: 15px;
        text-align: center;
    }

    .album-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 5px 0;
    }

    .album-count {
        font-size: 14px;
        color: #64748b;
    }

    /* Masonry Layout */
    .masonry-layout {
        column-count: 3;
        column-gap: 20px;
    }

    /* Grid Layout */
    .grid-layout {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    @media (max-width: 991px) {
        .masonry-layout { column-count: 2; }
    }
    @media (max-width: 575px) {
        .masonry-layout { column-count: 1; }
        .grid-layout { grid-template-columns: 1fr; }
    }

    .gallery-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        margin-bottom: 20px;
        display: block;
    }

    .grid-layout .gallery-item {
        margin-bottom: 0;
        height: 250px;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .grid-layout .gallery-item img {
        height: 250px;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    .play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 50px;
        color: white;
        opacity: 0.8;
        pointer-events: none;
        z-index: 2;
    }

    .gallery-item:hover .play-icon {
        opacity: 1;
        color: var(--brand-color);
        transition: 0.3s;
    }
    
    .layout-switcher {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .ls-btn {
        background: transparent;
        border: none;
        font-size: 20px;
        color: #94a3b8;
        cursor: pointer;
    }
    
    .ls-btn.active {
        color: var(--brand-color);
    }
</style>

<div class="gallery-container">
    
    <div class="gallery-tabs">
        <button class="g-btn active" onclick="showTab('albums')">Albums</button>
        <button class="g-btn" onclick="showTab('all')">All Photos & Videos</button>
    </div>

    <!-- Albums View -->
    <div id="view-albums">
        <div class="albums-grid">
            @foreach($albums as $album)
                @php
                    $photoCount = \App\Models\Gallery::where('album_id', $album->id)->count();
                @endphp
                <div class="album-card" onclick="openAlbum({{ $album->id }})">
                    <div class="album-img-wrap">
                        @if($album->cover_image)
                            <img src="{{ asset('backend/images/gallery/'.$album->cover_image) }}" alt="{{ $album->name }}">
                        @else
                            <div style="width:100%;height:100%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:40px;"><i class="bi bi-folder-fill"></i></div>
                        @endif
                    </div>
                    <div class="album-info">
                        <h3 class="album-title">{{ $album->name }}</h3>
                        <div class="album-count">{{ $photoCount }} items</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- All Items View -->
    <div id="view-all" style="display: none;">
        
        <div class="layout-switcher">
            <button class="ls-btn active" id="btn-masonry" onclick="switchLayout('masonry')"><i class="bi bi-columns"></i></button>
            <button class="ls-btn" id="btn-grid" onclick="switchLayout('grid')"><i class="bi bi-grid-fill"></i></button>
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
                        // Extract YT thumbnail if it's youtube
                        if (str_contains($src, 'youtube') || str_contains($src, 'youtu.be')) {
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $src, $match);
                            $thumb = isset($match[1]) ? "https://img.youtube.com/vi/{$match[1]}/hqdefault.jpg" : "";
                        } else {
                            $thumb = ""; // fallback for vimeo/others, handled by lightgallery
                        }
                    }
                @endphp
                <a href="{{ $src }}" class="gallery-item album-item-{{ $item->album_id }}" data-sub-html="{{ $item->caption }}">
                    @if($item->type === 'video_url')
                        <i class="bi bi-play-circle-fill play-icon"></i>
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="Video Thumbnail">
                        @else
                            <div style="width:100%;height:100%;background:#1a1a2e;"></div>
                        @endif
                    @else
                        <img src="{{ $thumb }}" alt="Gallery Photo">
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
    function showTab(tab) {
        document.querySelectorAll('.g-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('view-albums').style.display = 'none';
        document.getElementById('view-all').style.display = 'none';
        
        if (tab === 'albums') {
            event.target.classList.add('active');
            document.getElementById('view-albums').style.display = 'block';
            
            // Show all items again when going back to albums
            document.querySelectorAll('.gallery-item').forEach(el => el.style.display = 'block');
        } else {
            event.target.classList.add('active');
            document.getElementById('view-all').style.display = 'block';
        }
    }

    function openAlbum(albumId) {
        // Switch to "All Items" tab visually but filter by album
        document.querySelectorAll('.g-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('view-albums').style.display = 'none';
        document.getElementById('view-all').style.display = 'block';
        
        document.querySelectorAll('.gallery-item').forEach(el => {
            if(el.classList.contains('album-item-' + albumId)) {
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        });
    }

    function switchLayout(type) {
        const wrapper = document.getElementById('gallery-wrapper');
        const btnMasonry = document.getElementById('btn-masonry');
        const btnGrid = document.getElementById('btn-grid');
        
        if (type === 'masonry') {
            wrapper.className = 'masonry-layout';
            btnMasonry.classList.add('active');
            btnGrid.classList.remove('active');
        } else {
            wrapper.className = 'grid-layout';
            btnGrid.classList.add('active');
            btnMasonry.classList.remove('active');
        }
    }

    // Initialize LightGallery with Video support
    document.addEventListener('DOMContentLoaded', function() {
        const galleryEl = document.getElementById('gallery-wrapper');
        if (galleryEl && typeof lightGallery !== 'undefined') {
            lightGallery(galleryEl, {
                plugins: [lgVideo],
                speed: 500,
                download: false
            });
        }
    });
</script>

@endsection
