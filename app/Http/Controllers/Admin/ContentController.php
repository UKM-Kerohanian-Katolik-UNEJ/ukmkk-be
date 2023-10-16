<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("contents.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("contents.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "judul" => "required",
            "user_id" => "numeric|exists:users,id",
            "kategori" => "required|in:Proker,Artikel",
            "konten" => "required",
            "gambar_andalan_konten" => "required|max:1024|mimes:webp",
            "galeri_konten" => "nullable|max:5000|mimes:zip",
        ]);

        DB::beginTransaction();
        try
        {
            $content = Content::create([
                "judul" => $request->judul,
                "slug" => Str::slug($request->judul),
                "user_id" => Auth::user()->id,
                "kategori" => $request->kategori,
                "konten" => $request->konten
            ]);

            $content->addMediaFromRequest("gambar_andalan_konten")->toMediaCollection("gambar_utama_konten");

            // Cek apakah user input galeri
            if($request->hasFile("galeri_konten"))
            {
                // get file zip
                $zip_file = $request->file("galeri_konten");

                // extract zip
                $zip = new ZipArchive;
                $zip->open($zip_file);
                $zip->extractTo(public_path() . "/zip/");
                $zip->close();

                // get all gallery
                $galleries = File::allFiles(public_path()."/zip/");
                $is_valid = true;
                // loop galleries to store into media
                foreach($galleries as $gallery)
                {
                    // check if file is jpg, png, or jpeg
                    $extension = $gallery->getExtension();
                    if($extension == "webp") {
                        $content->addMedia($gallery->getRealPath())->toMediaCollection("galeri_konten");
                        File::delete($gallery->getRealPath());
                    } else {
                        File::delete($gallery->getRealPath());
                        $is_valid = false;
                    }
                }

                if(!$is_valid){
                    DB::rollBack();
                    session()->flash('error', 'File yang didalam zip harus berupa webp');
                    File::delete($zip_file);
                    return redirect()->back();
                }

                DB::commit();
                return redirect()->route("admin.konten.index")->with("success", "Data berhasil disimpan");
            }
        }catch(Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Content $konten)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $konten)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $konten)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $konten)
    {
        //
    }

    public function comment(Content $konten)
    {
        return view("contents.comments")->with([
            "content" => $konten
        ]);
    }

    public function destroy_comment(Comment $komentar)
    {
        $komentar->delete();
        return redirect()->back()->with("success", "Data berhasil dihapus");
    }
}
