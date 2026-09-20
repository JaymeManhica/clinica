@props(['name', 'title' => null])

<div class="flex items-center gap-2.5">
    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold shrink-0" aria-hidden="true">
        {{ \Illuminate\Support\Str::of($name)->explode(' ')->map(fn ($n) => $n[0])->take(2)->implode('') }}
    </div>
    <div class="min-w-0">
        <p class="text-sm font-medium text-gray-800 leading-tight truncate">{{ $name }}</p>
        @if ($title)
            <p class="text-xs text-gray-500 leading-tight truncate">{{ $title }}</p>
        @endif
    </div>
</div>
