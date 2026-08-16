<?php

use Livewire\Component;
use App\Models\Gallery;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::app')] class extends Component
{
    public $photos;

    public function mount()
    {
        $this->photos = Gallery::where('is_published', true)->latest()->get();
    }
};
?>

<div class="max-w-6xl mx-auto space-y-8 pb-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <a href="{{ route('participant.dashboard') }}" class="text-sm text-gray-500 hover:text-[#C0A062] flex items-center gap-1 mb-2 transition-colors">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">Event Gallery</h1>
            <p class="text-gray-500 mt-1">Exclusive documentations and highlights from GIHES 2026.</p>
        </div>
    </div>

    @if(count($photos) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($photos as $photo)
                <div class="group relative aspect-square bg-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer">
                    
                    <img src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}" 
                         alt="{{ $photo->title }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                        <p class="text-white font-bold text-sm tracking-wide translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            {{ $photo->title ?? 'GIHES Documentation' }}
                        </p>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-12 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-gray-500 font-medium">The gallery is currently empty.</p>
            <p class="text-xs text-gray-400 mt-1">Photos will be uploaded by the committee during the event.</p>
        </div>
    @endif

</div>