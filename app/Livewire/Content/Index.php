<?php

namespace App\Livewire\Content;

use App\Models\Content;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $pagination = 25;
    public $kategori;
    public $pencarian;

    public function render()
    {
        $contents = Content::where("judul", "like", "%" . $this->pencarian . "%")->whereUserId(Auth::user()->id);
        if($this->kategori)
        {
            $contents->whereKategori($this->kategori);
        }
        return view('livewire.content.index')->with([
            "contents" => $contents->with(["ContentViews", "Media"])->paginate($this->pagination)
        ]);
    }
}
