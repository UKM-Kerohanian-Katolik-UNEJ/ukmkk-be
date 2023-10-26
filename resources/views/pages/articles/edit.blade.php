<x-app-layout>
    @push('style')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    @endpush
    <x-slot name="header">
        {{ __('Edit Konten') }}
    </x-slot>
    <div class="p-4 bg-white rounded-lg shadow-xs">
        <form method="post" action="{{ route("admin.artikel.update", $artikel->slug) }}" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <div class="mb-3">
                <label class="label font-semibold">Judul</label>
                <input type="text" placeholder="Masukkan judul konten" class="input input-bordered w-full @error("judul")
                    input-error
                @enderror" name="judul" value="{{ $artikel->judul }}"/>
                @error('judul')
                    <span class="text-red-700">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="label font-semibold">Gambar Utama <i>(Berupa file .webp)</i></label>
                <img src={{ $artikel->getFirstMediaUrl("gambar_andalan_konten") }} width="200px" id="preview" />
                <input type="file" name="gambar_andalan_konten" class="file-input file-input-bordered w-full @error("gambar_andalan_konten")
                    file-input-error
                @enderror" id="gambar_andalan_konten" value={{ $artikel->getFirstMediaUrl("gambar_andalan_konten") }}/>
                @error('gambar_andalan_konten')
                    <span class="text-red-700">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="label font-semibold">Konten</label>
                <textarea name="konten">
                    {{ $artikel->konten }}
                </textarea>
                @error('konten')
                    <span class="text-red-700">{{ $message }}</span>
                @enderror
            </div>
            
            {{-- <div class="mb-3">
                <label class="label font-semibold">Galeri (Boleh dilewati) <i>(Berupa file .zip dan berisi file .webp)</i></label>
                <input type="file" name="galeri_konten" class="file-input file-input-bordered w-full @error("galeri_konten")
                    file-input-error
                @enderror"/>
                @error('galeri_konten')
                    <span class="text-red-700">{{ $message }}</span>
                @enderror
            </div> --}}

            <div class="flex gap-3 mt-7">
                <button class="btn btn-primary">Simpan</button>
                <button type="reset" class="btn btn-error">Reset</button>
                <a href="{{ route("admin.artikel.index") }}" class="btn btn-warning">Kembali</a>
            </div>
        </form> 
    </div>
    @push('script')
        @if (session("error"))
            <script>
                Swal.fire(
                    "Gagal",
                    `{{ session("error") }}`,
                    "error"
                );
            </script>
        @endif 
        
    @endpush
    @push('script')
    <script>
        CKEDITOR.replace('konten');
    </script>
    @endpush
</x-app-layout>
