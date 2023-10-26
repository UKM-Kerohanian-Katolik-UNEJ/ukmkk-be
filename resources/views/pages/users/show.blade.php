<x-app-layout>
    <x-slot name="header">
        {{ __('Lihat Anggota') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <div class="flex flex-col lg:flex-row lg:justify-start lg:ml-10 lg:gap-20 items-center gap-10">
            <div class="avatar lg:-mt-[350px] flex-col gap-3">
                <div class="w-[200px] h-[200px] rounded-full border border-gray-500 mb-3">
                    <img
                        class="object-cover w-full h-full rounded-full"
                        src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                        alt=""
                        loading="lazy"
                        />
                </div>

                <a target="_blank" href="{{ $member->getFirstMediaUrl("ktm") }}" class="btn btn-success btn-outline font-bold">Lihat KTM</a>
                <form id="form-status" action="{{ route("admin.anggota.update", $member->slug) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select class="select select-bordered w-full max-w-xs" name="status" onchange="updateStatus()">
                        <option value="Pending" {{ $member->is_verified === "Pending" ? "selected" : "" }}>Pending</option>
                        <option value="Verified" {{ $member->is_verified === "Verified" ? "selected" : "" }}>Verified</option>
                        <option value="Rejected" {{ $member->is_verified === "Rejected" ? "selected" : "" }}>Rejected</option>
                    </select>
                </form>
            </div>
            <div class="data-diri flex flex-col gap-2 text-center lg:text-left">
                <div class="data-akademisi mb-3 border-2 border-gray-200 rounded-xl p-5">
                    <h3 class="font-semibold text-xl text-gray-300">Data Akademisi</h3>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Nama</h5>
                        <p>{{ $member->nama }}</p>
                    </div>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Email</h5>
                        <p>{{ $member->email }}</p>
                    </div>
                    <div class="flex flex-col">
                        <h5 class="font-medium">NIM</h5>
                        <p>{{ $member->nim }}</p>
                    </div>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Fakultas</h5>
                        <p>{{ $member->fakultas_asal }}</p>
                    </div>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Tahun Masuk</h5>
                        <p>{{ $member->tahun_masuk }}</p>
                    </div>
                </div>

                <div class="data-religi mb-3 border-2 border-gray-200 rounded-xl p-5">
                    <h3 class="font-semibold text-xl text-gray-300">Data Religiositas</h3>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Paroki</h5>
                        <p>{{ $member->paroki_asal }}</p>
                    </div>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Talenta</h5>
                        <ul>
                            @forelse ($member->MemberSkills as $talent)
                                <li>- {{ $talent->nama_skill }}</li>
                            @empty
                                <li>Data Kosong</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="data-diri mb-3 border-2 border-gray-200 rounded-xl p-5">
                    <h3 class="font-semibold text-xl text-gray-300">Data Diri</h3>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Alamat</h5>
                        <p>{{ $member->alamat_rumah }}, {{ $member->kabupaten_asal }}, {{ $member->provinsi_asal }}</p>
                    </div>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Nomor HP</h5>
                        <p>{{ $member->no_hp }}</p>
                    </div>
                    <div class="flex flex-col">
                        <h5 class="font-medium">Tanggal Lahir</h5>
                        <p>{{ $member->tanggal_lahir }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        @if (session("success"))
            <script>
                Swal.fire(
                    "Berhasil",
                    `{{ session("success") }}`,
                    "success"
                );
            </script>
            
        @elseif(session("error"))
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
        function updateStatus()
        {
            Swal.fire({
                title: 'Apakah anda yakin merubah status?',
                showDenyButton: true,
                icon: "question",
                confirmButtonText: 'Simpan',
                denyButtonText: `Batal`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $("form#form-status").submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>
