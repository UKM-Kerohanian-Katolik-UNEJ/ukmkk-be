<?php

namespace App\Livewire\Article;

use App\Models\article;
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
        $articles = Content::where("judul", "like", "%" . $this->pencarian . "%")->whereUserId(Auth::user()->id);
        if($this->kategori)
        {
            $articles->whereKategori($this->kategori);
        }
        return view('livewire.article.index')->with([
            "articles" => $articles->whereKategori("Artikel")->with(["ContentViews", "Media"])->paginate($this->pagination)
        ]);
    }
}
