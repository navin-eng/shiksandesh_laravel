@extends('backend.pages.layout.master')
@push('b-title', 'Gallery')
@section('backend-content')

<!-- Cropper.js for Image Touchup -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    .cropper-container { max-height: 500px; width: 100%; }
    .cropper-img-wrapper { text-align: center; background: #f1f5f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;}
    .cropper-img-wrapper img { max-width: 100%; display: block; }
    .gallery-item-card { position:relative; border-radius:8px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1); background:#fff; display:flex; flex-direction:column;}
    .gallery-item-actions { position:absolute; top:10px; right:10px; display:flex; gap:5px; z-index:3; opacity:0; transition:0.3s; }
    .gallery-item-card:hover .gallery-item-actions { opacity:1; }
    .btn-g-action { background:rgba(255,255,255,0.9); border:none; padding:5px 8px; border-radius:4px; color:#334155; cursor:pointer; box-shadow:0 2px 5px rgba(0,0,0,0.2); }
    .btn-g-action:hover { background:#fff; color:var(--brand-color); }
    .btn-g-danger:hover { color:#ef4444; }
</style>

<div class="admin-page-header">
  <div><h1 class="aph-title">Gallery Management</h1><p class="aph-sub">Manage your albums, upload photos, embed videos, and touch up images.</p></div>
  <div>
      <button type="button" class="btn-admin btn-admin-light me-2" data-bs-toggle="modal" data-bs-target="#albumModal">
        <i class="bi bi-folder-plus"></i> Create Album
      </button>
      <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-plus-circle"></i> Add Items
      </button>
  </div>
</div>

{{-- Create Album Modal --}}
<div class="modal fade" id="albumModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-folder-plus text-green me-2"></i>Create Album</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('gallery.album.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <label class="admin-label">Album Name</label>
          <input type="text" name="name" class="admin-input" required placeholder="E.g. Sports Week 2024">
          
          <label class="admin-label mt-3">Cover Image (Optional)</label>
          <input type="file" name="cover_image" class="admin-input" accept="image/*">
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Save Album</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Album Modal --}}
<div class="modal fade" id="editAlbumModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-pencil-square text-green me-2"></i>Edit Album</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editAlbumForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <label class="admin-label">Album Name</label>
          <input type="text" name="name" id="edit_album_name" class="admin-input" required>
          
          <label class="admin-label mt-3">Cover Image (Optional)</label>
          <input type="file" name="cover_image" class="admin-input" accept="image/*">
          <p class="admin-input-hint">Leave blank to keep the current cover image.</p>
          
          <label class="admin-label mt-2">Status</label>
          <select name="status" id="edit_album_status" class="admin-input">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Update Album</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Item Modal (Caption & Album Assignment) --}}
<div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-pencil-square text-blue me-2"></i>Edit Media Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editItemForm" action="" method="POST">
        @csrf
        <div class="modal-body">
            
            <div id="touchupSection" style="display:none; text-align:center; margin-bottom: 20px;">
                <button type="button" class="btn-admin btn-admin-light w-100" onclick="openCropModal()">
                    <i class="bi bi-crop"></i> Open Image Touchup Tool
                </button>
            </div>

            <label class="admin-label">Assign to Album</label>
            <select name="album_id" id="edit_item_album" class="admin-input mb-3">
                <option value="">-- No Album (Unassigned) --</option>
                @foreach($albums as $album)
                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                @endforeach
            </select>
            
            <label class="admin-label">Caption (Optional)</label>
            <input type="text" name="caption" id="edit_item_caption" class="admin-input mb-3" placeholder="Enter a caption for this item">

        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Crop/Touchup Modal --}}
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-crop text-green me-2"></i>Image Touchup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <div class="cropper-img-wrapper">
              <img id="imageToCrop" src="" alt="Picture">
          </div>
          <div class="d-flex justify-content-center gap-2 mt-3">
              <button class="btn-admin btn-admin-light" onclick="cropper.rotate(-90)" title="Rotate Left"><i class="bi bi-arrow-counterclockwise"></i></button>
              <button class="btn-admin btn-admin-light" onclick="cropper.rotate(90)" title="Rotate Right"><i class="bi bi-arrow-clockwise"></i></button>
              <button class="btn-admin btn-admin-light" onclick="cropper.scaleX(cropper.getData().scaleX === -1 ? 1 : -1)" title="Flip Horizontal"><i class="bi bi-symmetry-horizontal"></i></button>
              <button class="btn-admin btn-admin-light" onclick="cropper.scaleY(cropper.getData().scaleY === -1 ? 1 : -1)" title="Flip Vertical"><i class="bi bi-symmetry-vertical"></i></button>
              <button class="btn-admin btn-admin-light" onclick="cropper.reset()" title="Reset"><i class="bi bi-arrow-repeat"></i> Reset</button>
          </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <form id="cropForm" method="POST" action="">
              @csrf
              <input type="hidden" name="cropped_image" id="croppedImageData">
              <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn-admin btn-admin-primary" onclick="submitCrop()"><i class="bi bi-save"></i> Save Touchup</button>
          </form>
      </div>
    </div>
  </div>
</div>

{{-- Add Items Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-plus-circle text-green me-2"></i>Add to Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      {{-- Tabs --}}
      <ul class="nav nav-tabs admin-tabs px-3 pt-3" style="border-bottom:none;">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-image"><i class="bi bi-image"></i> Image</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-video"><i class="bi bi-camera-video"></i> Video (URL)</a></li>
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
                {{-- Image Tab --}}
                <div class="tab-pane fade show active" id="tab-image">
                    <label class="admin-label">Image Source</label>
                    <select class="admin-input mb-3" onchange="toggleImageInput(this.value)">
                        <option value="upload">Upload from Computer</option>
                        <option value="image_url">Image URL</option>
                    </select>

                    <div id="image-upload-wrapper">
                        <input type="hidden" name="type" value="image" id="typeInput">
                        <label class="admin-label">Select Images (multiple allowed)</label>
                        <input type="file" name="gallery[]" id="fileInput" multiple class="admin-input" accept="image/*">
                        <p class="admin-input-hint">Supported: JPG, PNG, WebP. Max 5MB each. Images will be automatically compressed.</p>
                    </div>

                    <div id="image-url-wrapper" style="display:none;">
                        <label class="admin-label">Image URL</label>
                        <input type="url" name="url" id="urlInput" class="admin-input" placeholder="https://example.com/image.jpg" disabled>
                    </div>
                </div>
                
                {{-- Video Tab --}}
                <div class="tab-pane fade" id="tab-video">
                    <label class="admin-label">Video URL (YouTube/Vimeo)</label>
                    <input type="url" name="video_url" id="videoUrlInput" class="admin-input" placeholder="https://youtube.com/watch?v=..." disabled>
                </div>
            </div>

        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Save Items</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Main Page Content with Tabs for Albums and Items --}}
<div class="admin-card">
  <div class="admin-card-header p-0" style="border-bottom:1px solid var(--admin-border);">
      <ul class="nav nav-tabs admin-tabs px-3 pt-2" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#content-items">
                <i class="bi bi-grid-3x3-gap-fill"></i> All Items <span class="badge bg-secondary ms-1">{{ $gallery->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#content-albums">
                <i class="bi bi-folder-fill"></i> Manage Albums <span class="badge bg-secondary ms-1">{{ $albums->count() }}</span>
            </a>
        </li>
      </ul>
  </div>
  <div class="admin-card-body p-4 tab-content">
      
      {{-- Gallery Items Tab --}}
      <div class="tab-pane fade show active" id="content-items">
        <div class="gallery-admin-row">
          @forelse($gallery as $item)
            <div class="gallery-item-card">
              
              <div class="gallery-item-actions">
                  @if($item->album_id && $item->type === 'image')
                    <a href="{{ route('gallery.album.setCover', ['album_id' => $item->album_id, 'gallery_id' => $item->id]) }}" class="btn-g-action" title="Set as Album Cover">
                        <i class="bi bi-image"></i>
                    </a>
                  @endif
                  <button type="button" class="btn-g-action" title="Edit/Assign" onclick="editItem({{ $item->id }}, '{{ addslashes($item->caption ?? '') }}', '{{ $item->album_id ?? '' }}', '{{ $item->type }}', '{{ $item->type === 'image' ? asset('backend/images/gallery/'.$item->file_path) : '' }}')">
                      <i class="bi bi-pencil-fill"></i>
                  </button>
                  <a class="btn-g-action btn-g-danger" href="{{ route('gallery.delete', $item->id) }}" onclick="return confirm('Delete this item?')" title="Delete">
                    <i class="bi bi-trash-fill"></i>
                  </a>
              </div>
              
              <div style="flex-grow:1; display:flex; flex-direction:column; position:relative;">
                  {{-- Badge for media type --}}
                  @if($item->type === 'video_url')
                    <div style="position:absolute;top:10px;left:10px;background:rgba(0,0,0,0.7);color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;z-index:2;"><i class="bi bi-play-fill text-danger"></i> Video</div>
                    <div style="width:100%;height:150px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;color:#fff;">
                        <i class="bi bi-play-circle" style="font-size:40px;opacity:0.5;"></i>
                    </div>
                  @elseif($item->type === 'image_url')
                    <div style="position:absolute;top:10px;left:10px;background:rgba(0,0,0,0.7);color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;z-index:2;"><i class="bi bi-link"></i> URL</div>
                    <img src="{{ $item->url }}" alt="Gallery URL" style="width:100%;height:150px;object-fit:cover;">
                  @else
                    <img src="{{ asset('backend/images/gallery/'.$item->file_path) }}" alt="Gallery Photo" style="width:100%;height:150px;object-fit:cover;">
                  @endif
              </div>
              
              <div style="padding:10px; background:#f8fafc; font-size:13px;">
                  @if($item->album_id)
                    <div style="color:var(--brand-color); font-weight:600; margin-bottom:4px;"><i class="bi bi-folder-fill"></i> {{ $item->album->name ?? 'Album' }}</div>
                  @else
                    <div style="color:#94a3b8; font-style:italic; margin-bottom:4px;">Unassigned</div>
                  @endif
                  @if($item->caption)
                    <div style="color:#475569; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->caption }}">{{ $item->caption }}</div>
                  @endif
              </div>
            </div>
          @empty
            <div style="width:100%;text-align:center;padding:60px;color:#718096;grid-column:1/-1;">
              <i class="bi bi-images" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
              No items added yet. Click "Add Items" to start.
            </div>
          @endforelse
        </div>
      </div>
      
      {{-- Albums Tab --}}
      <div class="tab-pane fade" id="content-albums">
        <table class="table admin-table mb-0 mt-2">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Name</th>
                    <th>Photos</th>
                    <th>Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($albums as $album)
                <tr>
                    <td>
                        @if($album->cover_image)
                            <img src="{{ asset('backend/images/gallery/'.$album->cover_image) }}" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        @else
                            @php
                                $latestImage = \App\Models\Gallery::where('album_id', $album->id)->where('type', 'image')->whereNotNull('file_path')->latest()->first();
                            @endphp
                            @if($latestImage)
                                <img src="{{ asset('backend/images/gallery/'.$latestImage->file_path) }}" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                            @else
                                <div style="width:50px;height:50px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;"><i class="bi bi-folder"></i></div>
                            @endif
                        @endif
                    </td>
                    <td style="font-weight:600;">{{ $album->name }}</td>
                    <td><span class="badge-admin badge-blue">{{ \App\Models\Gallery::where('album_id', $album->id)->count() }} items</span></td>
                    <td>
                        @if($album->status === 'active')
                            <span class="badge-admin badge-green">Active</span>
                        @else
                            <span class="badge-admin badge-red">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-admin btn-admin-light" style="padding:4px 8px;" onclick="editAlbum({{ $album->id }}, '{{ addslashes($album->name) }}', '{{ $album->status }}')"><i class="bi bi-pencil"></i></button>
                        <a href="{{ route('gallery.album.delete', $album->id) }}" class="btn-admin btn-admin-danger" style="padding:4px 8px;" onclick="return confirm('Delete this album and all its photos?');"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:#94a3b8;">No albums created yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
      </div>
      
  </div>
</div>

<script>
    // Tab toggle logic for the Add Items Modal
    document.querySelectorAll('#uploadModal .nav-link').forEach(link => {
        link.addEventListener('shown.bs.tab', function(e) {
            let target = e.target.getAttribute('href');
            let typeInput = document.getElementById('typeInput');
            let fileInput = document.getElementById('fileInput');
            let urlInput = document.getElementById('urlInput');
            let videoUrlInput = document.getElementById('videoUrlInput');
            
            // Reset disabled states
            fileInput.disabled = true;
            urlInput.disabled = true;
            videoUrlInput.disabled = true;
            
            if (target === '#tab-image') {
                // If it's the image tab, re-check the image source select
                let source = document.querySelector('#tab-image select').value;
                toggleImageInput(source);
            } else if (target === '#tab-video') {
                typeInput.value = 'video_url';
                videoUrlInput.disabled = false;
                videoUrlInput.name = 'url'; // Send video URL as 'url'
                urlInput.name = ''; // Prevent sending empty image url
            }
        });
    });

    function toggleImageInput(source) {
        let typeInput = document.getElementById('typeInput');
        let fileInput = document.getElementById('fileInput');
        let urlInput = document.getElementById('urlInput');
        let videoUrlInput = document.getElementById('videoUrlInput');
        
        let fileWrap = document.getElementById('image-upload-wrapper');
        let urlWrap = document.getElementById('image-url-wrapper');
        
        videoUrlInput.disabled = true; // Ensure video is disabled
        videoUrlInput.name = '';

        if (source === 'upload') {
            typeInput.value = 'image';
            fileWrap.style.display = 'block';
            urlWrap.style.display = 'none';
            fileInput.disabled = false;
            urlInput.disabled = true;
            urlInput.name = '';
        } else {
            typeInput.value = 'image_url';
            fileWrap.style.display = 'none';
            urlWrap.style.display = 'block';
            fileInput.disabled = true;
            urlInput.disabled = false;
            urlInput.name = 'url';
        }
    }

    // Edit Album Modal logic
    function editAlbum(id, name, status) {
        document.getElementById('editAlbumForm').action = '/admin/dashboard/gallery/albums/update/' + id;
        document.getElementById('edit_album_name').value = name;
        document.getElementById('edit_album_status').value = status;
        var editModal = new bootstrap.Modal(document.getElementById('editAlbumModal'));
        editModal.show();
    }

    // Edit Item Modal logic
    let currentImageToCropId = null;
    let currentImageToCropUrl = null;
    let cropper = null;

    function editItem(id, caption, album_id, type, localImageUrl) {
        document.getElementById('editItemForm').action = '/admin/dashboard/gallery/update/' + id;
        document.getElementById('edit_item_caption').value = caption;
        document.getElementById('edit_item_album').value = album_id;
        
        // Show touchup button only if it's a locally uploaded image
        let touchupSec = document.getElementById('touchupSection');
        if (type === 'image' && localImageUrl) {
            touchupSec.style.display = 'block';
            currentImageToCropId = id;
            currentImageToCropUrl = localImageUrl;
        } else {
            touchupSec.style.display = 'none';
            currentImageToCropId = null;
            currentImageToCropUrl = null;
        }
        
        var editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
        editModal.show();
    }

    // Cropper.js Integration
    function openCropModal() {
        if(!currentImageToCropUrl) return;
        
        // Hide edit item modal
        var editModal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
        editModal.hide();
        
        // Setup crop form action
        document.getElementById('cropForm').action = '/admin/dashboard/gallery/crop/' + currentImageToCropId;
        
        let image = document.getElementById('imageToCrop');
        image.src = currentImageToCropUrl;
        
        // Show crop modal
        var cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
        cropModal.show();
        
        // Initialize cropper after modal is shown to calculate dimensions properly
        document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(image, {
                viewMode: 1,
                autoCropArea: 1,
                responsive: true
            });
        }, { once: true });
    }

    function submitCrop() {
        if (!cropper) return;
        
        // Get base64 cropped image
        const canvas = cropper.getCroppedCanvas();
        if(!canvas) {
            alert('Could not crop image.');
            return;
        }
        
        const dataURL = canvas.toDataURL('image/jpeg', 0.9);
        document.getElementById('croppedImageData').value = dataURL;
        document.getElementById('cropForm').submit();
    }
</script>

@endsection
