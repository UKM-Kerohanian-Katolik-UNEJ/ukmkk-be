<?php

namespace App\Livewire\Article;

use App\Models\article;
use App\Models\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $pagination = 25;
    public $pencarian;

    public function render()
    {
        // buat query get all article dan pencarian dengan relasi User dan ContentViews
        $articles = Content::with(["User", "ContentViews"])
                    ->when($this->pencarian, function($query) {
                        $query->where("judul", "like", "%{$this->pencarian}%");
                    })
                    ->whereUserId(Auth::user()->id)
                    ->whereKategori("Artikel")
                    ->orderByDesc("created_at")
                    ->paginate($this->pagination);
                    
        return view('livewire.article.index')->with([
            "articles" => $articles
        ]);
    }
}
