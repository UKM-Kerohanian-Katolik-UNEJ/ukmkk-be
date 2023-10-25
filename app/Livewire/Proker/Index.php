<?php

namespace App\Livewire\Proker;

use App\Models\Content;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $pagination = 2;
    public $kategori;
    public $pencarian;

    public function render()
    {
        $prokers = Content::where("judul", "like", "%" . $this->pencarian . "%")->whereUserId(Auth::user()->id);
        if($this->kategori)
        {
            $prokers->whereKategori($this->kategori);
        }
        return view('livewire.proker.index')->with([
            "prokers" => $prokers->whereKategori("Proker")->with(["ContentViews", "Media"])->paginate($this->pagination)
        ]);
    }
}
