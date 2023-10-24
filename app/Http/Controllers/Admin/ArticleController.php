<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use App\Models\ContentView;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("articles.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("articles.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "judul" => "required|unique:contents,judul",
            "user_id" => "numeric|exists:users,id",
            "konten" => "required",
            "gambar_andalan_konten" => "required|max:1024|mimes:webp",
            "galeri_konten" => "nullable|max:5000|mimes:zip",
        ]);

        DB::beginTransaction();
        try
        {
            $artikel = Content::create([
                "judul" => $request->judul,
                "slug" => Str::slug($request->judul),
                "kategori" => "Artikel",
                "user_id" => Auth::user()->id,
                "konten" => $request->konten
            ]);

            $artikel->addMediaFromRequest("gambar_andalan_konten")->toMediaCollection("gambar_andalan_konten");

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
                        $artikel->addMedia($gallery->getRealPath())->toMediaCollection("galeri_konten");
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
            }
            DB::commit();
            return redirect()->route("admin.artikel.index")->with("success", "Data berhasil disimpan");
        }catch(Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Content $artikel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $artikel)
    {
        if($artikel->user_id == Auth::user()->id )
        {
            return view("articles.edit")->with([
                'artikel' => $artikel
            ]);
        }
        abort(403, "Anda tidak bisa memodifikasi artikel yang bukan buatan Anda");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $artikel)
    {
        if($artikel->user_id == Auth::user()->id )
        {
            $request->validate([
                "judul" => "required|unique:contents,judul," . $artikel->id,
                "user_id" => "numeric|exists:users,id",
                "konten" => "required",
                "gambar_andalan_konten" => "max:1024|mimes:webp",
                "galeri_konten" => "nullable|max:5000|mimes:zip",
            ]);
    
            DB::beginTransaction();
            try {
                $artikel->update([
                    "judul" => $request->judul,
                    "slug" => Str::slug($request->judul),
                    "kategori" => "Artikel", // "Artikel,
                    "user_id" => Auth::user()->id,
                    "konten" => $request->konten
                ]);
    
                if($request->hasFile("gambar_andalan_konten"))
                {
                    $artikel->clearMediaCollection("gambar_andalan_konten");
                    $artikel->addMediaFromRequest("gambar_andalan_konten")->toMediaCollection("gambar_andalan_konten");
                }
    
                // check if gallery is not null
                if($request->hasFile("galeri_konten")) {
                    // get zip file
                    $zip_file = $request->file("galeri_konten");
    
                    // extract it
                    $zip = new ZipArchive;
                    $zip->open($zip_file);
                    $zip->extractTo(public_path()."/zip/");
                    $zip->close();
    
                    // get all gallery
                    $galleries = File::allFiles(public_path()."/zip/");
    
                    $is_valid = true;
                    // loop galleries to store into media
                    foreach($galleries as $gallery)
                    {
                        $extension = $gallery->getExtension();
                        if($extension == "webp") {
                            $artikel->addMedia($gallery->getRealPath())->toMediaCollection("galeri_konten");
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
                }
                DB::commit();
                return redirect()->route("admin.artikel.index")->with("success", "Data berhasil diubah");
            } catch (Exception $e) {
                //throw $th;
                DB::rollBack();
                return redirect()->back()->with("error", $e->getMessage());
            }
        }
        abort(403, "Anda tidak bisa memodifikasi artikel yang bukan buatan Anda");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $artikel)
    {
        if($artikel->user_id == Auth::user()->id )
        {
            // Delete Viewers
            ContentView::whereContentId($artikel->id)->delete();

            // Delete Comment
            Comment::whereContentId($artikel->id)->delete();
    
            // Delete media and data
            $artikel->clearMediaCollection("gambar_andalan_konten");
            $artikel->clearMediaCollection("galeri_konten");
            $artikel->delete();
            return redirect()->back()->with("success", "Data berhasil dihapus");
        }
        abort(403, "Anda tidak bisa memodifikasi artikel yang bukan buatan Anda");
    }

    public function comments(Content $artikel)
    {
        if($artikel->user_id == Auth::user()->id )
        {
            $comments = Comment::with("Member")->whereContentId($artikel->id)->paginate(10);
            return view("contents.comments")->with([
                "comments" => $comments
            ]);
        }
        abort(403, "Anda tidak bisa memodifikasi artikel yang bukan buatan Anda");
    }

    public function destroy_comment(Comment $komentar)
    {
        $komentar->delete();
        return redirect()->back()->with("success", "Data berhasil dihapus");
    }
}
