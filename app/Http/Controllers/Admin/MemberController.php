<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberSkill;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index()
    {
        return view("pages.users.index");
    }

    public function show(Member $anggotum)
    {
        return view("pages.users.show")->with([
            "member" => $anggotum
        ]);
    }

    public function update(Request $request, Member $anggotum)
    {
        DB::beginTransaction();
        try
        {
            $anggotum->update([
                "is_verified" => $request->status
            ]);
            DB::commit();
            return redirect()->back()->with("success", "Status verifikasi berhasil diubah");
        }catch(Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function destroy(Member $anggotum)
    {
        $anggotum->clearMediaCollection("ktm");
        MemberSkill::whereMemberId($anggotum->id)->delete();
        $anggotum->delete();
        return redirect()->back()->with("success", "Data berhasil dihapus");
    }

    public function destroyAll()
    {
        $members = Member::whereIsVerified("Rejected")->get();
        DB::beginTransaction();
        try
        {
            if(count($members) === 0)
            {
                DB::rollBack();
                return redirect()->back()->with("error", "Tidak ada mahasiswa yang memiliki status rejected");
            }
            foreach ($members as $member) {
                # code...
                $member->clearMediaCollection("ktm");
                MemberSkill::whereMemberId($member->id);
                $member->delete();
            }
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil dihapus");
        }catch(Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
