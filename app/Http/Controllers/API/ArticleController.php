<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use App\Models\ContentView;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['createComments']]);
    }

    public function index()
    {
        $articles = Content::with(["User", "media"])->whereKategori("Artikel")->orderByDesc("created_at")->paginate(10);
        return ResponseFormatter::success("Data Artikel Berhasil Diambil", $articles);
    }

    public function show($slug)
    {
        DB::beginTransaction(); // Mulai transaksi database
        try {
            $artikel = Content::with(["User", "Comments.Member", "media"])
                ->whereSlug($slug)
                ->whereKategori("Artikel")
                ->lockForUpdate()
                ->first();

            if (!$artikel) {
                DB::rollBack(); // Batalkan transaksi jika data tidak ditemukan
                return ResponseFormatter::error("Data Artikel Tidak Ada", 404);
            }

            // Lakukan update jumlah viewer
            // jika belum ada datanya, tambahkan data content views
            // jika ada increment viewsnya
            $contentViews = ContentView::whereContentId($artikel->id)->whereTahun(date("Y"))->whereBulan(date("m"))->first();
            if(!$contentViews)
            {
                ContentView::create([
                    "content_id" => $artikel->id,
                    "tahun" => date("Y"),
                    "bulan" => date("m"),
                    "viewers" => 1
                ]);
            } else {
                $contentViews->increment("viewers");
            }
            DB::commit(); // Commit transaksi jika semuanya berhasil
            return ResponseFormatter::success("Data Artikel Berhasil Diambil", $artikel->load("ContentViews"));
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika terjadi kesalahan
            return ResponseFormatter::error("Terjadi kesalahan saat mengambil data artikel", 500);
        }
    }

    public function createComments(Content $artikel, Request $request)
    {
        $request->validate([
            "content_id" => "exists:contents,id",
            "member_id" => "exists:members,id",
            "konten" => "required"
        ]);

        DB::beginTransaction();
        try
        {
            $comment = Comment::create([
                "content_id" => $artikel->id,
                "member_id" => Auth::user()->id,
                "konten" => $request->konten
            ]);
            DB::commit();
            return ResponseFormatter::success("Komentar berhasil ditambahkan", $comment);
        } catch(Exception $e)
        {
            DB::rollBack();
            return ResponseFormatter::error("Terjadi kesalahan saat menambahkan komentar", $e->getMessage(), 500);
        }
    }
}
