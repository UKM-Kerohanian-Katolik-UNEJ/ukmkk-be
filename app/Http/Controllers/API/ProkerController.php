<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use App\Models\ContentView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProkerController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api", ["only" => ["createComments"]]);
    }

    public function index()
    {
        $prokers = Content::with(["User", "media"])->whereKategori("Proker")->orderByDesc("created_at")->paginate(10);
        return ResponseFormatter::success("Data Proker Berhasil Diambil", $prokers);
    }

    public function show($slug)
    {
        DB::beginTransaction(); // Mulai transaksi database
        try {
            $proker = Content::with(["User", "Comments.Member", "media"])
                ->whereSlug($slug)
                ->whereKategori("Proker")
                ->lockForUpdate()
                ->first();

            if (!$proker) {
                DB::rollBack(); // Batalkan transaksi jika data tidak ditemukan
                return ResponseFormatter::error("Data Proker Tidak Ada", 404);
            }

            // Lakukan update jumlah viewer
            // jika belum ada datanya, tambahkan data content views
            // jika ada increment viewsnya
            $contentViews = ContentView::whereContentId($proker->id)->whereTahun(date("Y"))->whereBulan(date("m"))->first();
            if(!$contentViews)
            {
                ContentView::create([
                    "content_id" => $proker->id,
                    "tahun" => date("Y"),
                    "bulan" => date("m"),
                    "viewers" => 1
                ]);
            } else {
                $contentViews->increment("viewers");
            }
            DB::commit(); // Commit transaksi jika semuanya berhasil
            return ResponseFormatter::success("Data Proker Berhasil Diambil", $proker->load("ContentViews"));
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika terjadi kesalahan
            return ResponseFormatter::error("Data Proker Gagal Diambil", $e->getMessage(), 500);
        }
    }

    public function createComments(Request $request, Content $proker)
    {
        $request->validate([
            "content_id" => ["exists:contents,id"],
            "member_id" => ["exists:members,id"],
            "konten" => ["required", "string"]
        ]);

        DB::beginTransaction(); // Mulai transaksi database
        try {
            $comment = Comment::create([
                "content_id" => $proker->id,
                "member_id" => Auth::guard("api")->user()->id,
                "konten" => $request->konten
            ]);

            DB::commit(); // Commit transaksi jika semuanya berhasil
            return ResponseFormatter::success("Komentar Berhasil Ditambahkan", $comment);
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika terjadi kesalahan
            return ResponseFormatter::error("Komentar Gagal Ditambahkan", $e->getMessage(), 500);
        }
    }
}
