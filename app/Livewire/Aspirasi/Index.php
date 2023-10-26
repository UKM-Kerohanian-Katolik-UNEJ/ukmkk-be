<?php

namespace App\Livewire\Aspirasi;

use App\Models\Aspirasi;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $pagination = 25;
    public $bulanTahun; // berisi yyyy-mm

    public function render()
    {
        $aspirasis = Aspirasi::query();
        if($this->bulanTahun)
        {
            $aspirasis->whereYear("created_at", substr($this->bulanTahun, 0, 4));
            $aspirasis->whereMonth("created_at", substr($this->bulanTahun, 5, 2));
        }

        return view('livewire.aspirasi.index')->with([
            "aspirasis" => $aspirasis->orderByDesc("created_at")->paginate($this->pagination)
        ]);
    }

    public function resetTanggal()
    {
        $this->bulanTahun = null;
    }
}
