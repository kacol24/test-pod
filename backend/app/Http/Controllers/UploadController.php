<?php 

namespace Core\Http\Backend;

use Validator, Image;
use Illuminate\Http\Request;
use Core\Http\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller {
  public function content(Request $request) {
    $validator = Validator::make($request->all(), [
      'upload' => 'required|image|mimes:png,jpeg,jpg,gif',
    ]);

    if ($validator->fails()) {
      return array("error" => array(
        "message"=> "The image upload failed because the image was too big (max 1.5MB)."
      ));
    }

    $file = $request->file('upload');
    $extension = $file->getClientOriginalExtension();
    $filename =  sha1(Str::random(32)).".".$extension;
    $images = array();

    $path = storage_path('app/contents');
    if(!file_exists($path)){
      mkdir($path, 0755, true);
    }
    $filetarget = $path.'/'.$filename;
    $img = Image::make($file)->save($filetarget);
    if(env('FILESYSTEM_DRIVER') == 's3') {
      Storage::putFileAs('/contents', $filetarget, $filename);
      unlink($filetarget);
    }

    

    return array('url' => asset('contents/'.$filename));
  }
}