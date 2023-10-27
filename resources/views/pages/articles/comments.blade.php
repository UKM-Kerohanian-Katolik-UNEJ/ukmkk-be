<x-app-layout>
    <x-slot name="header">
        {{ __('Komentar Konten') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">
    @foreach ($comments as $comment)
        <div class="group px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="flex justify-between gap-3">
                    <div class="comments flex">
                        <div class="relative hidden w-10 h-10 mr-3 rounded-full md:block">
                            <img
                            class="object-cover w-full h-full rounded-full"
                            src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                            alt=""
                            loading="lazy"
                            />
                        </div>
    
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col">
                                <h3 class="font-semibold">{{ $comment->Member->nama }}</h3>
                                <p class="text-xs text-gray-400">{{ $comment->Member->fakultas_asal }}</p>
                            </div>
                            <p class="text-justify">{{ $comment->konten }}</p>
                        </div>
                    </div>

                    <form action="{{ route("admin.artikel.komentar.destroy", $comment->id) }}" method="POST">
                        @csrf
                        @method("DELETE")
                        <button
                            class="items-center justify-end px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                            aria-label="Delete"
                        >
                            <svg
                            class="w-5 h-5"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            >
                            <path
                                fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            ></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
