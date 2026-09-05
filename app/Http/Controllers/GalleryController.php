<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
class GalleryController extends Controller
{
    public function index()
    {
        // Now fetching individually structured gallery rows.
        $gallery = Gallery::latest()->get();
        $albums = \App\Models\GalleryAlbum::all();
        return view('backend.pages.gallery.table', compact('gallery', 'albums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'album_id' => 'nullable|exists:gallery_albums,id',
            'type' => 'required|in:image,image_url,video_url',
            'caption' => 'nullable|string|max:255',
        ]);

        if ($request->type === 'image') {
            $request->validate([
                'gallery' => 'required|array|min:1',
                'gallery.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $img) {
                    $extension = $img->getClientOriginalExtension();
                    $imageName = Str::random(20) . time() . '.' . $extension;
                    
                    // Basic native GD compression (if it's a JPEG or PNG)
                    $tempPath = $img->getPathname();
                    $destPath = public_path('backend/images/gallery/' . $imageName);
                    
                    if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                        $source = imagecreatefromjpeg($tempPath);
                        imagejpeg($source, $destPath, 75); // 75% quality compression
                        imagedestroy($source);
                    } elseif (strtolower($extension) == 'png') {
                        $source = imagecreatefrompng($tempPath);
                        imagepng($source, $destPath, 8); // 8/9 compression
                        imagedestroy($source);
                    } else {
                        $img->move('backend/images/gallery/', $imageName);
                    }

                    $galleryItem = new Gallery();
                    $galleryItem->album_id = $request->album_id;
                    $galleryItem->type = 'image';
                    $galleryItem->file_path = $imageName;
                    $galleryItem->caption = $request->caption;
                    $galleryItem->save();
                }
            }
        } else {
            // URL or Video
            $request->validate([
                'url' => 'required|url',
            ]);
            
            $galleryItem = new Gallery();
            $galleryItem->album_id = $request->album_id;
            $galleryItem->type = $request->type;
            $galleryItem->url = $request->url;
            $galleryItem->caption = $request->caption;
            $galleryItem->save();
        }

        Alert::success('Saved', 'Gallery item(s) saved successfully');
        return back();
    }

    public function galleryDelete($id)
    {
        $galleryItem = Gallery::findOrFail($id);
        
        if ($galleryItem->type === 'image' && $galleryItem->file_path) {
            $imagePath = public_path('backend/images/gallery/' . $galleryItem->file_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        
        $galleryItem->delete();
        Alert::success('Success','Item Deleted');
        return back();
    }
}
