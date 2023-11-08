<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}
        <div class="flex items-center justify-between">
        @foreach(\App\Models\Candidate::all() as $data)
        <div class="relative p-4 flex-1">
            <div
                class="rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900 shadow-xl shadow-black/5 ring-1 ring-slate-700/10">
                <div class="flex items-center p-4 pb-0">
                    <img src="{{ url('storage/'.$data->picture) }}" alt="" class="h-11 w-11 flex-none rounded-full">
                        <div class="ml-4 flex-auto" style="margin-left: 20px">
                            <div class="font-medium">{{$data->name}}(Kandidat {{$loop->iteration}})</div>
                            <div class="mt-1 text-slate-500">Suara: {{$data->votes}}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
