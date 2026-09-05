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

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->caption = $request->caption;
        $gallery->album_id = $request->album_id;
        $gallery->save();

        Alert::success('Updated', 'Item updated successfully!');
        return back();
    }

    public function cropImage(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        $request->validate([
            'cropped_image' => 'required|string',
        ]);

        if ($gallery->type !== 'image' || !$gallery->file_path) {
            return back()->with('error', 'Only uploaded images can be cropped.');
        }

        // The cropped image comes as a base64 data URL. Fix any spaces that were converted from '+' during POST.
        $base64Data = str_replace(' ', '+', $request->cropped_image);
        
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                return back()->with('error', 'Invalid image type for cropping.');
            }
            
            $base64Data = base64_decode($base64Data);
            
            if ($base64Data === false) {
                return back()->with('error', 'Base64 decode failed.');
            }
        } else {
            return back()->with('error', 'Invalid base64 string.');
        }

        // Save over the original file
        $filePath = public_path('backend/images/gallery/' . $gallery->file_path);
        
        // Use GD to save the cropped image properly instead of raw file_put_contents
        $image = imagecreatefromstring($base64Data);
        if ($image !== false) {
            if ($type == 'png') {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                imagepng($image, $filePath, 9);
            } else {
                imagejpeg($image, $filePath, 90);
            }
            imagedestroy($image);
        } else {
            // fallback
            file_put_contents($filePath, $base64Data);
        }

        // Touch the model to update the updated_at timestamp (used for cache busting)
        $gallery->touch();

        Alert::success('Success', 'Image cropped successfully!');
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
