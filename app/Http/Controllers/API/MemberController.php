<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberSkill;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
class MemberController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|string|email",
            "password" => "required|string|min:8",
        ],
        [
            // buat message hanya untuk required dan unique saja
            "required" => ":attribute harus diisi",
            "unique" => ":attribute sudah terdaftar",
        ]);
        $credentials = $request->only(["email", "password"]);

        if (! $token = auth("api")->attempt($credentials)) {
            return ResponseFormatter::error("Login gagal", 401);
        }

        return ResponseFormatter::success("Berhasil login", [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth("api")->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            "nama" => "required|string|max:255|unique:members,nama",
            "email" => "required|string|email|max:255|unique:members,email",
            "no_hp" => "required|string|max:255|unique:members,no_hp",
            "tanggal_lahir" => "required|date",
            "nim" => "required|string|max:12|unique:members,nim",
            "tahun_masuk" => "required|date_format:Y",
            "fakultas_asal" => "required|string|max:255",
            "sekolah_asal" => "required|string|max:255",
            "paroki_asal" => "required|string|max:255",
            "provinsi_asal" => "required|string|max:255|exists:provinces,name",
            "kabupaten_asal" => "required|string|max:255|exists:regencies,name",
            "alamat_rumah" => "required|string",
            "alamat_kost" => "nullable|string",
            "golongan_darah" => "required|string|in:A,B,AB,O",
            "password" => "required|string|min:8",
            "member_skills" => "nullable|array",
            "ktm" => "required|image|max:2048|mimes:png",
        ],
        [
            // buat message hanya untuk required dan unique saja
            "required" => ":attribute harus diisi",
            "unique" => ":attribute sudah terdaftar",
        ]);

        DB::beginTransaction();
        try
        {
            $member = Member::create([
                "nama" => $request->nama,
                "slug" => Str::slug($request->nama),
                "email" => $request->email,
                "no_hp" => $request->no_hp,
                "tanggal_lahir" => $request->tanggal_lahir,
                "nim" => $request->nim,
                "tahun_masuk" => $request->tahun_masuk,
                "fakultas_asal" => $request->fakultas_asal,
                "sekolah_asal" => $request->sekolah_asal,
                "paroki_asal" => $request->paroki_asal,
                "provinsi_asal" => $request->provinsi_asal,
                "kabupaten_asal" => $request->kabupaten_asal,
                "alamat_rumah" => $request->alamat_rumah,
                "alamat_kost" => $request->alamat_kost,
                "golongan_darah" => $request->golongan_darah,
                "password" => bcrypt($request->password),
            ]);

            $member->addMediaFromRequest("ktm")->toMediaCollection("ktm");

            if(!empty($request->member_skills))
            {
                MemberSkill::create([
                    "member_id" => $member->id,
                    "nama_skill" => ucwords($request->nama_skill),
                ]);
            }

            DB::commit();
            return ResponseFormatter::success("Berhasil mendaftar", $member->load("MemberSkills"));
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error("Gagal mendaftar", $e->getMessage());
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return ResponseFormatter::success("Berhasil mengambil data member", auth("api")->user()->load("MemberSkills"));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth("api")->logout();

        return ResponseFormatter::success("Berhasil logout");
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return ResponseFormatter::success("Berhasil refresh token", [
            'access_token' => auth("api")->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth("api")->factory()->getTTL() * 3600
        ]);
    }


}
