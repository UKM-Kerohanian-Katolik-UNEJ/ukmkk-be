<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ContentView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Content::with(["User"])->whereKategori("Artikel")->orderByDesc("created_at")->paginate(10);
        return ResponseFormatter::success("Data Artikel Berhasil Diambil", $articles);
    }

    public function show($slug)
    {
        DB::beginTransaction(); // Mulai transaksi database
    try {
        $artikel = Content::with(["User"])
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


        return ResponseFormatter::success("Data Artikel Berhasil Diambil", $artikel);
    }
}
