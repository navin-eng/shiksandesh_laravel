@extends('backend.pages.layout.master')
@push('b-title', 'Gallery')
@section('backend-content')

<div class="admin-page-header">
  <div><h1 class="aph-title">Photo/Video Gallery</h1><p class="aph-sub">Upload photos, add image URLs, or embed YouTube/Vimeo videos.</p></div>
  <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
    <i class="bi bi-plus-circle"></i> Add Items
  </button>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-plus-circle text-green me-2"></i>Add to Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      {{-- Tabs --}}
      <ul class="nav nav-tabs admin-tabs px-3 pt-3" style="border-bottom:none;">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-upload"><i class="bi bi-cloud-upload"></i> Upload Photos</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-url"><i class="bi bi-link"></i> Add URL (Image/Video)</a></li>
      </ul>

      <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            
            <label class="admin-label">Assign to Album (Optional)</label>
            <select name="album_id" class="admin-input mb-3">
                <option value="">-- No Album --</option>
                @foreach($albums as $album)
                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                @endforeach
            </select>
            
            <label class="admin-label">Caption (Optional)</label>
            <input type="text" name="caption" class="admin-input mb-3" placeholder="Enter a short description">

            <div class="tab-content">
                {{-- Upload Tab --}}
                <div class="tab-pane fade show active" id="tab-upload">
                    <input type="hidden" name="type" value="image" id="typeInput">
                    <label class="admin-label">Select Images (multiple allowed)</label>
                    <input type="file" name="gallery[]" multiple class="admin-input" accept="image/*">
                    <p class="admin-input-hint">Supported: JPG, PNG, WebP. Max 5MB each. Images will be automatically compressed to save space.</p>
                </div>
                
                {{-- URL Tab --}}
                <div class="tab-pane fade" id="tab-url">
                    <label class="admin-label">Media Type</label>
                    <select class="admin-input mb-3" onchange="document.getElementById('typeInput').value = this.value; if(this.value!='image'){document.getElementById('typeInput').disabled = true; this.name='type'}else{document.getElementById('typeInput').disabled = false; this.name=''}">
                        <option value="image_url">Image URL</option>
                        <option value="video_url">Video URL (YouTube/Vimeo)</option>
                    </select>

                    <label class="admin-label">Media URL</label>
                    <input type="url" name="url" class="admin-input" placeholder="https://youtube.com/watch?v=... or https://example.com/image.jpg">
                </div>
            </div>

        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="admin-card">
  <div class="admin-card-header">
    <span class="card-title"><i class="bi bi-grid-3x3-gap-fill"></i> All Gallery Items</span>
    <span class="badge-admin badge-green">{{ $gallery->count() }} items</span>
  </div>
  <div class="admin-card-body">
    <div class="gallery-admin-row">
      @forelse($gallery as $item)
        <div style="position:relative;border-radius:8px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
          <a class="deleteImageBTN" href="{{ route('gallery.delete', $item->id) }}">
            <i class="bi bi-x"></i>
          </a>
          
          {{-- Badge for media type --}}
          @if($item->type === 'video_url')
            <div style="position:absolute;bottom:10px;left:10px;background:rgba(0,0,0,0.7);color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;z-index:2;"><i class="bi bi-play-fill text-danger"></i> Video</div>
            <div style="width:100%;height:150px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;color:#fff;">
                <i class="bi bi-play-circle" style="font-size:40px;opacity:0.5;"></i>
            </div>
          @elseif($item->type === 'image_url')
            <div style="position:absolute;bottom:10px;left:10px;background:rgba(0,0,0,0.7);color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;z-index:2;"><i class="bi bi-link"></i> URL</div>
            <img src="{{ $item->url }}" alt="Gallery URL" style="width:100%;height:150px;object-fit:cover;">
          @else
            @if($item->album_id)
                <div style="position:absolute;bottom:10px;left:10px;background:rgba(0,0,0,0.7);color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;z-index:2;"><i class="bi bi-folder"></i> Album</div>
            @endif
            <img src="{{ asset('backend/images/gallery/'.$item->file_path) }}" alt="Gallery Photo" style="width:100%;height:150px;object-fit:cover;">
          @endif
        </div>
      @empty
        <div style="width:100%;text-align:center;padding:60px;color:#718096;grid-column:1/-1;">
          <i class="bi bi-images" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
          No items added yet. Click "Add Items" to start.
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection
