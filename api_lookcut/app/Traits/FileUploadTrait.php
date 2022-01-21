<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait FileUploadTrait
{
    /**
     * File upload trait used in controllers to upload files
     */
    public function saveFiles(Request $request)
    {
        if (! file_exists(public_path('uploads'))) {
            mkdir(public_path('uploads'), 0777);
            mkdir(public_path('uploads/thumb'), 0777);
        }
        $finalRequest = $request;
        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                if ($request->has($key . '_max_width') && $request->has($key . '_max_height')) {
                        // Check file width
                    $filename = time() . '-' . $request->file($key)->getClientOriginalName();
                    $file     = $request->file($key);
                    $image    = Image::make($file);
                    if (! file_exists(public_path('uploads/thumb'))) {
                        mkdir(public_path('uploads/thumb'), 0777, true);
                    }
                    Image::make($file)->resize(50, 50)->save(public_path('uploads/thumb') . '/' . $filename);
                    $width  = $image->width();
                    $height = $image->height();
                    if ($width > $request->{$key . '_max_width'} && $height > $request->{$key . '_max_height'}) {
                        $image->resize($request->{$key . '_max_width'}, $request->{$key . '_max_height'});
                    } elseif ($width > $request->{$key . '_max_width'}) {
                        $image->resize($request->{$key . '_max_width'}, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } elseif ($height > $request->{$key . '_max_width'}) {
                        $image->resize(null, $request->{$key . '_max_height'}, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $image->save(public_path('uploads') . '/' . $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                } else {
                    $filename = time() . '-' . $request->file($key)->getClientOriginalName();
                    $request->file($key)->move(public_path('uploads'), $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                }
            }
        }
        return $finalRequest;
    }

    /**
     * Guarda Archivo en la carpeta enviada
     *
     * @param [type] $request
     * @param [type] $name
     * @param [type] $folder
     * @return void
     */
    public function uploadFile($request, $name, $folder)
    {
        $filename = null;
        if($request->hasFile($name)){
            $filename = $request->file($name)->store($folder);
        }
        return $filename;
    }

    /**
     * 
     */
    public function uploadFiles($request, $name, $folder) {
        $name_files = array();
        if(is_array($request->$name)){

            if($request->hasFile($name)){

                foreach ($request->$name as $file) {
                    $name_file = $file->store($folder);
                    array_push($name_files, $name_file);
                }
            }
        }
        return json_encode($name_files);
    }

    /**
     * Undocumented function
     *
     * @param [type] $base_file_name
     * @param [type] $folder
     * @param [type] $request
     * @param [type] $name_field
     * @param [type] $prefix
     * @return void
     */
    public function upfileWithName($base_file_name, $folder, $request, $name_field, $prefix)
    {
        $filename = null;
        if ($request->hasFile($name_field)) {
            $extension = $request->file($name_field)->getClientOriginalExtension();        
            $name_file = "{$base_file_name}_{$prefix}.{$extension}";
            $filename = $request->file($name_field)->storeAs( $folder,  $name_file, 'public' );
        }
        return $filename;
    }

    public function upfileFimasDigitalesBase64($request, $name, $folder, $usu_codigo){

        $signature = $request->$name;
        $signatureFileName = $usu_codigo."_".uniqid().'.png';
        $signature = str_replace('data:image/png;base64,', '', $signature);
        $signature = str_replace(' ', '+', $signature);
        $data = base64_decode($signature);
        $file = '../../panel/storage/app/public/upfilesspe/firma_digital/'.$signatureFileName;
        if(file_put_contents($file, $data)){
            return $signatureFileName;
        } else {
            return false;
        }
    }
}