<x-app-layout>
    @push('style')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    @endpush
    <x-slot name="header">
        {{ __('Edit Konten') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="label font-semibold">Judul</label>
                <input type="text" placeholder="Masukkan judul konten" class="input input-bordered w-full" />
            </div>

            <div class="mb-3">
                <label class="label font-semibold">Tipe Konten</label>
                <select name="" class="select select-bordered w-full">
                    <option value="Artikel">Artikel</option>
                    <option value="Program Kerja">Program Kerja</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="label font-semibold">Gambar Utama <i>(Berupa file .webp)</i></label>
                <input type="file" class="file-input file-input-bordered w-full" />
            </div>

            <div class="mb-3">
                <label class="label font-semibold">Konten</label>
                <textarea name="textarea"></textarea>
            </div>
            
            <div class="mb-3">
                <label class="label font-semibold">Galeri <i>(Berupa file .zip dan berisi file .webp)</i></label>
                <input type="file" class="file-input file-input-bordered w-full" />
            </div>

            <div class="flex gap-3 mt-7">
                <button class="btn btn-primary">Simpan</button>
                <button type="reset" class="btn btn-error">Reset</button>
                <a href="{{ route("admin.konten.index") }}" class="btn btn-warning">Kembali</a>
            </div>
        </form> 
    </div>
    @push('script')
    <script>
        CKEDITOR.replace('textarea');
    </script>
    @endpush
</x-app-layout>
