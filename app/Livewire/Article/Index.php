<?php

namespace App\Livewire\Article;

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
        $contents = Content::where("judul", "like", "%" . $this->pencarian . "%")->whereUserId(Auth::user()->id);
        if($this->kategori)
        {
            $contents->whereKategori($this->kategori);
        }
        return view('livewire.article.index')->with([
            "contents" => $contents->whereKategori("Artikel")->with(["ContentViews", "Media"])->paginate($this->pagination)
        ]);
    }
}
