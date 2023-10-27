<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AspirasiController extends Controller
{
    public function createAspirasi(Request $request)
    {
        $request->validate([
            "nama" => "nullable|string",
            "aspirasi" => "required|string",
        ]);

        DB::beginTransaction();
        try
        {
            $aspirasi = Aspirasi::create([
                "nama" => $request->nama,
                "aspirasi" => $request->aspirasi,
            ]);

            DB::commit();
            return ResponseFormatter::success("Aspirasi berhasil dikirim", $aspirasi);

        }catch (Exception $e)
        {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), "Aspirasi gagal dikirim");
        }
    }
}
