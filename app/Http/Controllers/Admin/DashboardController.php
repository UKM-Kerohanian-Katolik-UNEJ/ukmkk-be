<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Data Card
        $prokers = Content::whereKategori("Proker")->count();
        $articles = Content::whereKategori("Artikel")->count();
        $pengurus = User::count();
        $anggota = Member::count();

        // Data Grafik
        /* Bar */
        $tahun = date("Y");
        $anggotaTerdaftar = DB::table('members')
                            ->select('tahun_masuk', DB::raw('COUNT(*) as jumlah'))
                            ->whereBetween('tahun_masuk', [$tahun - 5, $tahun])
                            ->groupBy("tahun_masuk")
                            ->orderBy("tahun_masuk")
                            ->get();
        $bar = [];
        foreach($anggotaTerdaftar as $dataBar)
        {
            $bar["tahun"][] = $dataBar->tahun_masuk;
            $bar["jumlah"][] = $dataBar->jumlah;
        }
        $pembacaProkerBulanan = DB::table("content_views")
                        ->selectRaw('content_views.bulan as bulan, SUM(viewers) as jumlah')
                        ->where("content_views.tahun", $tahun)
                        ->where("contents.kategori", "Proker")
                        ->join("contents", "contents.id", "=", "content_views.content_id")
                        ->groupBy("content_views.bulan")
                        ->orderBy("content_views.bulan")
                        ->get();

        $pembacaArtikelBulanan = DB::table("content_views")
                        ->selectRaw('content_views.bulan as bulan, SUM(viewers) as jumlah')
                        ->where("content_views.tahun", $tahun)
                        ->where("contents.kategori", "Artikel")
                        ->join("contents", "contents.id", "=", "content_views.content_id")
                        ->groupBy("content_views.bulan")
                        ->orderBy("content_views.bulan")
                        ->get();
        /* Line */
        $line = [];
        $line["bulan"] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "August", "Sept", "Oct", "Nov", "Dec"];
        $line["jumlah_pembaca_proker"] = [];
        $line["jumlah_pembaca_artikel"] = [];

        foreach ($pembacaProkerBulanan as $item) {
            $line["jumlah_pembaca_proker"][] = $item->jumlah;
        }

        foreach ($pembacaArtikelBulanan as $item) {
            $line["jumlah_pembaca_artikel"][] = $item->jumlah;
        }
        
        return view("dashboard", compact("prokers", "articles", "pengurus", "anggota", "bar", "line"));
    }
}
