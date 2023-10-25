<x-app-layout>
    @push('style')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    @endpush
    <x-slot name="header">
        {{ __('Buat Proker') }}
    </x-slot>
    <div class="p-4 bg-white rounded-lg shadow-xs">
        <form method="post" action="{{ route("admin.proker.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="label font-semibold">Judul</label>
                <input type="text" placeholder="Masukkan judul konten" class="input input-bordered w-full @error("judul")
                    input-error
                @enderror" name="judul" value="{{ old("judul") }}"/>
                @error('judul')
                    <span class="text-red-700">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="label font-semibold">Gambar Utama <i>(Berupa file .webp)</i></label>
                <img id="preview" width="200px">
                <input type="file" name="gambar_andalan_konten" class="file-input file-input-bordered w-full @error("gambar_andalan_konten")
                    file-input-error
                @enderror" id="gambar_andalan_konten"/>
                @error('gambar_andalan_konten')
                    <span class="text-red-700">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="label font-semibold">Konten</label>
                <textarea name="konten">{{ old("konten") }}</textarea>
                @error('konten')
                    <span class="text-red-700">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="label font-semibold">Galeri (Boleh dilewati) <i>(Berupa file .zip dan berisi file .webp)</i></label>
                <input type="file" name="galeri_konten" class="file-input file-input-bordered w-full @error("galeri_konten")
                    file-input-error
                @enderror"/>
                @error('galeri_konten')
                    <span class="text-red-700">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="label font-semibold">Jadwal</label>
                <div class="flex gap-3">
                    <div class="w-1/2">
                        <label class="label">Nama</label>
                        <input type="text" name="nama_jadwal[]" class="input input-bordered w-full @error("nama_jadwal")
                            input-error
                        @enderror" value="{{ old("nama_jadwal") }}" required/>
                        @error('nama_jadwal')
                            <span class="text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <label class="label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai[]" class="input input-bordered w-full @error("tanggal_mulai")
                            input-error
                        @enderror" value="{{ old("tanggal_mulai") }}" required/>
                        @error('tanggal_mulai')
                            <span class="text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <label class="label">Tanggal Selesai (Boleh dilewati)</label>
                        <input type="date" name="tanggal_selesai[]" class="input input-bordered w-full @error("tanggal_selesai")
                            input-error
                        @enderror" value="{{ old("tanggal_selesai") }}"/>
                        @error('tanggal_selesai')
                            <span class="text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Aksi</label>
                        <div class="flex gap-3">
                            <button type="button" class="btn btn-primary" id="tambah-jadwal">+</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="output mb-3">{{-- tempat output jadwal baru --}}</div>

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
        @elseif($errors->any())
            <script>
                Swal.fire(
                    "Gagal",
                    `{{ $errors->first() }}`,
                    "error"
                );
            </script>
        @endif 
        
    @endpush
    @push('script')
    <script>
        CKEDITOR.replace('konten');

        // tambah jadwal dan fitur hapus jadwal dengan jquery
        $(document).ready(function(){
            $("#tambah-jadwal").click(function(){
                $(".output").append(`
                    <div class="flex gap-3 mb-3">
                        <div class="w-1/2">
                            <label class="label">Nama</label>
                            <input type="text" name="nama_jadwal[]" class="input input-bordered w-full @error("nama_jadwal")
                                input-error
                            @enderror" value="{{ old("nama_jadwal") }}" required/>
                            @error('nama_jadwal')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-1/2">
                            <label class="label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai[]" class="input input-bordered w-full @error("tanggal_mulai")
                                input-error
                            @enderror" value="{{ old("tanggal_mulai") }}" required/>
                            @error('tanggal_mulai')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-1/2">
                            <label class="label">Tanggal Selesai (Boleh dilewati)</label>
                            <input type="date" name="tanggal_selesai[]" class="input input-bordered w-full @error("tanggal_selesai")
                                input-error
                            @enderror" value="{{ old("tanggal_selesai") }}"/>
                            @error('tanggal_selesai')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="label">Aksi</label>
                            <div class="flex gap-3">
                                <button type="button" class="btn btn-error hapus-jadwal">-</button>
                            </div>
                        </div>
                    </div>
                `);
            });

            // fitur hapus jadwal
            $(document).on("click", ".hapus-jadwal", function(){
                $(this).parent().parent().parent().remove();
            });
        });

    </script>
    @endpush
</x-app-layout>
