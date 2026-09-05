@extends('backend.pages.layout.master')
@push('b-title', 'Gallery Albums')
@section('backend-content')

<div class="admin-page-header">
  <div><h1 class="aph-title">Gallery Albums</h1><p class="aph-sub">Create albums to group your photos.</p></div>
  <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#albumModal">
    <i class="bi bi-folder-plus"></i> Create Album
  </button>
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

<div class="admin-card">
  <div class="admin-card-header">
    <span class="card-title"><i class="bi bi-folder"></i> All Albums</span>
  </div>
  <div class="admin-card-body p-0">
    <table class="table admin-table mb-0">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Name</th>
                <th>Photos</th>
                <th width="100">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($albums as $album)
            <tr>
                <td>
                    @if($album->cover_image)
                        <img src="{{ asset('backend/images/gallery/'.$album->cover_image) }}" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                    @else
                        <div style="width:50px;height:50px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;"><i class="bi bi-folder"></i></div>
                    @endif
                </td>
                <td style="font-weight:600;">{{ $album->name }}</td>
                <td><span class="badge-admin badge-blue">{{ \App\Models\Gallery::where('album_id', $album->id)->count() }}</span></td>
                <td>
                    <a href="{{ route('gallery.album.delete', $album->id) }}" class="btn-admin btn-admin-danger" style="padding:4px 8px;" onclick="return confirm('Delete this album and all its photos?');"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;padding:40px;color:#94a3b8;">No albums created yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
  </div>
</div>
@endsection
