<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use App\Models\ContentSchedule;
use App\Models\ContentView;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZipArchive;

class ProkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.proker.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.proker.create");
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
            "nama_jadwal" => "required|array",
            "nama_jadwal.*" => "string",
            "tanggal_mulai" => "required|array",
            "tanggal_mulai.*" => "date",
            "tanggal_selesai" => "required|array",
            "tanggal_selesai.*" => "nullable|date",
        ]);

        DB::beginTransaction();
        try
        {
            $proker = Content::create([
                "judul" => $request->judul,
                "slug" => Str::slug($request->judul),
                "kategori" => "proker",
                "user_id" => Auth::user()->id,
                "konten" => $request->konten
            ]);

            $proker->addMediaFromRequest("gambar_andalan_konten")->toMediaCollection("gambar_andalan_konten");

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
                        $proker->addMedia($gallery->getRealPath())->toMediaCollection("galeri_konten");
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

            // Cek panjang array nama, tanggal mulai, dan tanggal selesai
            if(count($request->nama_jadwal) != count($request->tanggal_mulai) || count($request->nama_jadwal) != count($request->tanggal_selesai))
            {
                DB::rollBack();
                return redirect()->back()->with("error", "Nama jadwal, tanggal mulai, dan tanggal selesai harus selaras");
            }

            // Simpan jadwal
            foreach($request->nama_jadwal as $key => $data)
            {
                ContentSchedule::create([
                    "content_id" => $proker->id,
                    "nama_jadwal" => $data,
                    "tanggal_mulai" => $request->tanggal_mulai[$key],
                    "tanggal_selesai" => $request->tanggal_selesai[$key],
                ]);
            }
            DB::commit();
            return redirect()->route("admin.proker.index")->with("success", "Data berhasil disimpan");
        }catch(Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Content $proker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $proker)
    {
        return view("pages.proker.edit", compact("proker"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $proker)
    {
        if($proker->user_id == Auth::user()->id)
        {
            $request->validate([
                "judul" => "required|unique:contents,judul,".$proker->id,
                "user_id" => "numeric|exists:users,id",
                "konten" => "required",
                "gambar_andalan_konten" => "nullable|max:1024|mimes:webp",
                "galeri_konten" => "nullable|max:5000|mimes:zip",
                "nama_jadwal" => "required|array",
                "nama_jadwal.*" => "string",
                "tanggal_mulai" => "required|array",
                "tanggal_mulai.*" => "date",
                "tanggal_selesai" => "required|array",
                "tanggal_selesai.*" => "nullable|date",
            ]);
    
            DB::beginTransaction();
            try
            {
                $proker->update([
                    "judul" => $request->judul,
                    "slug" => Str::slug($request->judul),
                    "kategori" => "proker",
                    "user_id" => Auth::user()->id,
                    "konten" => $request->konten
                ]);
    
                // Cek apakah user input gambar andalan
                if($request->hasFile("gambar_andalan_konten"))
                {
                    $proker->clearMediaCollection("gambar_andalan_konten");
                    $proker->addMediaFromRequest("gambar_andalan_konten")->toMediaCollection("gambar_andalan_konten");
                }
    
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
                            $proker->clearMediaCollection("galeri_konten");
                            $proker->addMedia($gallery->getRealPath())->toMediaCollection("galeri_konten");
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
    
                // Cek panjang array nama, tanggal mulai, dan tanggal selesai
                if(count($request->nama_jadwal) != count($request->tanggal_mulai) || count($request->nama_jadwal) != count($request->tanggal_selesai))
                {
                    DB::rollBack();
                    return redirect()->back()->with("error", "Nama jadwal, tanggal mulai, dan tanggal selesai harus selaras");
                }
    
                // Hapus jadwal lama
                ContentSchedule::whereContentId($proker->id)->delete();
    
                // Simpan jadwal
                foreach($request->nama_jadwal as $key => $data)
                {
                    ContentSchedule::create([
                        "content_id" => $proker->id,
                        "nama_jadwal" => $data,
                        "tanggal_mulai" => $request->tanggal_mulai[$key],
                        "tanggal_selesai" => $request->tanggal_selesai[$key],
                    ]);
                }
                DB::commit();
                return redirect()->route("admin.proker.index")->with("success", "Data berhasil diubah");
            }catch(Exception $e)
            {
                DB::rollBack();
                return redirect()->back()->with("error", $e->getMessage());
            }
        }
        abort(403, "Anda tidak bisa memodifikasi proker yang bukan buatan Anda");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $proker)
    {
        if($proker->user_id == Auth::user()->id)
        {
            // Delete Viewers
            ContentView::whereContentId($proker->id)->delete();

            // Delete Comment
            Comment::whereContentId($proker->id)->delete();

            // Delete schedule
            ContentSchedule::whereContentId($proker->id)->delete();

            // Delete media and data
            $proker->clearMediaCollection("gambar_andalan_konten");
            $proker->clearMediaCollection("galeri_konten");
            $proker->delete();
            return redirect()->back()->with("success", "Data berhasil dihapus");
        }
        abort(403, "Anda tidak bisa memodifikasi proker yang bukan buatan Anda");
    }

    public function comments(Content $proker)
    {
        if($proker->user_id == Auth::user()->id )
        {
            $comments = Comment::with("Member")->whereContentId($proker->id)->paginate(10);
            return view("pages.proker.comments")->with([
                "comments" => $comments
            ]);
        }
        abort(403, "Anda tidak bisa memodifikasi proker yang bukan buatan Anda");
    }

    public function destroy_comment(Comment $komentar)
    {
        $komentar->delete();
        return redirect()->back()->with("success", "Data berhasil dihapus");
    }

    public function galleries(Content $proker)
    {
        if($proker->user_id == Auth::user()->id)
        {
            return view("pages.proker.gallery")->with([
                "galleries" => $proker->getMedia("galeri_konten")
            ]);
        }
        abort(403, "Anda tidak bisa memodifikasi proker yang bukan buatan Anda");
    }

    public function destroy_gallery($id)
    {
        Media::find($id)->delete();
        return redirect()->back()->with("success", "Data berhasil dihapus");
    }
}
