<?php

namespace App\Livewire\Members;

use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $pagination = 25;
    public $tahun_masuk;
    public $pencarian;

    public function render()
    {
        $member = Member::where("nama", "like", "%" . $this->pencarian . "%");
        if($this->tahun_masuk)
        {
            $member = $member->whereTahunMasuk($this->tahun_masuk);
        }
        return view('livewire.members.index')->with([
            "members" => $member->paginate($this->pagination)
        ]);
    }
}
