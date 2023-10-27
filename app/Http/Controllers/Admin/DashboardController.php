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
        $pembacaProkerTahunan = DB::table('content_views')
                            ->select('content_views.tahun', DB::raw('SUM(content_views.viewers) as jumlah'))
                            ->join('contents', 'content_views.content_id', '=', 'contents.id')
                            ->where('contents.kategori', '=', 'Proker')
                            ->where("tahun", $tahun)
                            ->groupBy("tahun")
                            ->orderBy("tahun")
                            ->get();

        $line = [];
        $line["tahun"] = [];
        if($tahun == date("Y"))
        {
            $line["tahun"][] = $tahun;
        }

        foreach($pembacaProkerTahunan as $dataLine)
        {
            $line["jumlah_pembaca_proker"][] = $dataLine->jumlah;
        }

        $pembacaArtikelTahunan = DB::table('content_views')
                            ->select('content_views.tahun', DB::raw('SUM(content_views.viewers) as jumlah'))
                            ->join('contents', 'content_views.content_id', '=', 'contents.id')
                            ->where('contents.kategori', '=', 'Artikel')
                            ->where("tahun", $tahun)
                            ->groupBy("tahun")
                            ->orderBy("tahun")
                            ->get();

        foreach($pembacaArtikelTahunan as $dataLine)
        {
            $line["jumlah_pembaca_artikel"][] = $dataLine->jumlah;
        }
        
        return view("dashboard", compact("prokers", "articles", "pengurus", "anggota", "bar", "line"));
    }
}
