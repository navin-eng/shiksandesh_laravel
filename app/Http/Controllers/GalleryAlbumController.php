<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;

class GalleryAlbumController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::latest()->get();
        return view('backend.pages.gallery.albums', compact('albums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $album = new GalleryAlbum();
        $album->name = $request->name;
        $album->slug = Str::slug($request->name) . '-' . time();
        $album->status = $request->status ?? 'active';

        if ($request->hasFile('cover_image')) {
            $img = $request->file('cover_image');
            $extension = $img->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            
            // Simple GD compression if needed, but for cover image we can just move it for now
            // or just use move
            $img->move('backend/images/gallery/', $imageName);
            $album->cover_image = $imageName;
        }

        if ($album->save()) {
            Alert::success('Saved', 'Album created successfully');
        } else {
            Alert::error('Error', 'Could not create album');
        }
        return back();
    }

    public function delete($id)
    {
        $album = GalleryAlbum::findOrFail($id);
        
        if ($album->cover_image) {
            $imagePath = public_path('backend/images/gallery/' . $album->cover_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        
        // Photos inside the album will cascade delete in DB, but their files will remain.
        // For a full implementation, we should delete the photos' files too.
        $photos = \App\Models\Gallery::where('album_id', $id)->get();
        foreach($photos as $photo) {
            if($photo->file_path) {
                $p = public_path('backend/images/gallery/' . $photo->file_path);
                if (File::exists($p)) File::delete($p);
            }
        }

        $album->delete();
        Alert::success('Success', 'Album deleted');
        return back();
    }
}
