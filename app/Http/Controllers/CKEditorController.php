<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            // Upload file
            $uploadedFile = $request->file('upload');
            $originName = $uploadedFile->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $uploadedFile->getClientOriginalExtension();
            $fileName = Str::slug($fileName) . '-' . time() . '.' . $extension;
            
            // Pastikan direktori ada
            $uploadPath = 'uploads/content';
            $publicPath = public_path($uploadPath);
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Upload file ke direktori public
            $uploadedFile->move($publicPath, $fileName);
            
            // Buat URL absolut
            $url = asset($uploadPath . '/' . $fileName);
            
            // Cek apakah ini request CKEditor
            $funcNum = $request->input('CKEditorFuncNum');
            
            if ($funcNum) {
                // Untuk callback CKEditor
                $message = 'Gambar berhasil diupload';
                return response(
                    "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>"
                )->header('Content-Type', 'text/html');
            }
            
            // Untuk respons JSON
            return response()->json([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => $url
            ]);
        }
        
        return response()->json([
            'uploaded' => 0,
            'error' => [
                'message' => 'File tidak dapat diupload.'
            ]
        ]);
    }
    
    public function browse(Request $request)
    {
        $uploadPath = 'uploads/content';
        $publicPath = public_path($uploadPath);
        
        // Pastikan direktori ada
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }
        
        // Ambil semua file di direktori
        $files = [];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (is_dir($publicPath)) {
            $dirFiles = scandir($publicPath);
            foreach ($dirFiles as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if ($file != '.' && $file != '..' && in_array(strtolower($extension), $allowedExtensions)) {
                    $files[] = [
                        'name' => $file,
                        'url' => asset($uploadPath . '/' . $file)
                    ];
                }
            }
        }
        
        // Tampilkan dalam format HTML untuk browser file
        return view('admin.ckeditor.browse', [
            'files' => $files,
            'funcNum' => $request->input('CKEditorFuncNum')
        ]);
    }
}