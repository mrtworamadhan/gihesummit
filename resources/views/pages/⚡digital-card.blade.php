<?php

use Livewire\Component;
use App\Models\Participant;
use Livewire\Attributes\Layout;

new #[Layout('layouts::guest')] class extends Component
{
    public $participant;

    public function mount($uuid)
    {
        $this->participant = Participant::with(['user', 'registration'])->where('uuid_barcode', $uuid)->firstOrFail();
    }
};
?>

<div class="min-h-screen bg-white flex flex-col items-center justify-center p-4 font-sans">
    
    <div id="id-card-area" class="w-full max-w-sm bg-gradient-to-br from-[#12241C] to-[#2B2D26] rounded-2xl shadow-2xl overflow-hidden border border-[#C0A062]/30 relative">
        
        <div class="bg-white p-4 text-center border-b border-[#C0A062]/20 flex flex-col items-center">
            <img src="{{ asset('images/logo-gihes-rev.png') }}" class="h-10 object-contain mb-2" alt="GIHES Logo">
            <h2 class="text-[#C0A062] font-black tracking-widest uppercase text-[10px]">Official E-Pass GIHES 2026</h2>
        </div>

        <div class="p-6 text-center">
            
            <div class="bg-white p-3 rounded-xl mx-auto w-48 h-48 flex items-center justify-center shadow-inner mb-6">
                <img crossorigin="anonymous" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $participant->uuid_barcode }}" alt="QR Code" class="w-full h-full object-contain">
            </div>
            
            <h3 class="text-2xl font-black text-white leading-tight mb-1">{{ $participant->user->name }}</h3>
            <p class="text-[#C0A062] text-xs font-bold uppercase tracking-wider mb-4">{{ $participant->user->institution_name ?? 'Participant' }}-{{ $participant->province ?? ' ' }}</p>

            <div class="grid grid-cols-2 gap-2 mt-4 text-left border-t border-slate-700/50 pt-4">
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">Kategori</p>
                    <p class="text-sm font-bold text-white">{{ $participant->registration->role_at_summit ?? 'Delegate' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">Nomor Kamar</p>
                    <p class="text-sm font-bold text-white">
                        @if($participant->registration->room_id)
                            {{ $participant->registration->room->room_number }}
                        @elseif($participant->registration->room_type_preference === 'No Accommodation')
                            Tanpa Kamar
                        @else
                            Menunggu Alokasi
                        @endif
                    </p>
                </div>
            </div>
            
        </div>
        
        <div class="h-2 w-full bg-[#C0A062]"></div>
    </div>

    <div class="w-full max-w-sm mt-8 space-y-3">
        <button onclick="downloadCard()" id="btn-download" class="w-full bg-[#C0A062] hover:bg-[#a88a53] text-[#12241C] font-black tracking-widest uppercase py-4 px-4 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span>Simpan ke Galeri</span>
        </button>
        
        <p class="text-center text-slate-500 text-xs mt-2">
            Harap siapkan kode ini di HP Anda sebelum memasuki area acara.
        </p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadCard() {
            const btn = document.getElementById('btn-download');
            const originalContent = btn.innerHTML;
            
            btn.innerHTML = 'Memproses...';
            btn.disabled = true;

            const element = document.getElementById('id-card-area');

            html2canvas(element, { 
                scale: 3, 
                useCORS: true, 
                allowTaint: false,
                backgroundColor: '#0b1121' 
            }).then(canvas => {
                let link = document.createElement('a');
                link.download = 'GIHES_Pass_{{ \Illuminate\Support\Str::slug($participant->user->name) }}.png';
                link.href = canvas.toDataURL("image/png");
                
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                btn.innerHTML = originalContent;
                btn.disabled = false;
            }).catch(err => {
                console.error(err);
                alert('Gagal mengunduh gambar. Silakan gunakan fitur screenshot HP Anda.');
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        }
    </script>
</div>