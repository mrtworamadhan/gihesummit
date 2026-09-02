<?php

use Livewire\Component;
use App\Models\Gatekeeper;
use App\Models\Agenda;
use App\Models\Registration;
use App\Models\Participant;
use App\Models\AttendanceLog;
use Livewire\Attributes\Layout;

new #[Layout('layouts::gatekeeper')] class extends Component
{
    public $gatekeeper;
    public $agendas;
    public $selected_agenda_id = '';
    
    // Untuk notifikasi di layar
    public $scanStatus = ''; // 'success', 'warning', 'danger'
    public $scanMessage = '';
    public $participantInfo = null;

    // Untuk fitur "Force Accept"
    public $pendingRegistrationId = null;

    public $searchQuery = '';
    public $searchResults = [];

    public $confirmingRegistrationId = null;

    public function mount($token)
    {
        // 1. Validasi Magic Token
        $this->gatekeeper = Gatekeeper::where('magic_token', $token)
                                      ->where('is_active', true)
                                      ->firstOrFail();

        // 2. Ambil semua agenda yang aktif
        $this->agendas = Agenda::where('is_active', true)->get();
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 3) {
            $this->searchResults = [];
            return;
        }

        // Cari peserta berdasarkan nama
        $this->searchResults = Registration::with(['participant.user'])
            ->whereHas('participant.user', function($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%');
            })->limit(5)->get();
    }

    public function processManualSelect($registrationId)
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->resetAlerts();

        if (empty($this->selected_agenda_id)) {
            $this->showAlert('danger', 'Pilih agenda terlebih dahulu sebelum memproses!');
            return;
        }

        $agenda = Agenda::find($this->selected_agenda_id);
        $registration = Registration::with(['participant.user', 'room', 'payment'])->find($registrationId);
        
        if($registration) {
            $this->validateAndRecord($registration, $agenda);
        }
    }

    public function processScan($qrData)
    {
        $this->resetAlerts();

        if (empty($this->selected_agenda_id)) {
            $this->showAlert('danger', 'Pilih agenda terlebih dahulu sebelum melakukan scan!');
            return;
        }

        $agenda = Agenda::find($this->selected_agenda_id);
        
        $participant = Participant::with(['user', 'registration.room', 'registration.payment'])
                                  ->where('uuid_barcode', $qrData)
                                  ->first();

        if (!$participant || !$participant->registration) {
            $this->showAlert('danger', 'Barcode tidak valid atau peserta belum registrasi!');
            return;
        }

        $this->validateAndRecord($participant->registration, $agenda);
    }

    private function validateAndRecord($registration, $agenda)
    {
        $existingLog = AttendanceLog::where('agenda_id', $agenda->id)
                                    ->where('registration_id', $registration->id)
                                    ->whereIn('status', ['success', 'force_accepted'])
                                    ->first();

        if ($existingLog) {
            $waktu = $existingLog->created_at->format('H:i');
            $this->showAlert('warning', "SUDAH MASUK! Sudah tercatat pada jam {$waktu}.");
            return;
        }

        if ($agenda->type === 'check_in') {
            if ($registration->payment?->payment_status !== 'paid' && $registration->role_at_summit !== 'Special Guest') {
                $this->promptForceAccept($registration, 'Peserta belum lunas.');
                return;
            }
            if (!$registration->room_id) {
                $this->showAlert('danger', 'BLOKIR: Kamar belum di-assign oleh panitia!');
                return;
            }
        } 
        elseif ($agenda->type === 'class') {
            $hasClass = $registration->additionalClasses()->where('additional_classes.id', $agenda->additional_class_id)->exists();
            if (!$hasClass && $registration->role_at_summit !== 'Special Guest') {
                $this->promptForceAccept($registration, 'Tidak terdaftar di kelas tambahan ini.');
                return;
            }
        }

        $this->previewAttendance($registration, $agenda);
    }

    private function previewAttendance($registration, $agenda)
    {
        $info = [
            'name' => $registration->participant?->user?->name ?? 'Nama Tidak Diketahui',
            'institution' => $registration->participant?->user?->institution_name ?? '-',
            'role' => $registration->role_at_summit ?? 'Participant',
        ];

        if ($agenda->type === 'check_in') {
            $info['room_type'] = $registration->room_type_preference ?? '-';
            if ($registration->room) {
                $info['room_number'] = $registration->room->room_number ?? 'Nomor tidak ada';
                if ($info['room_type'] === 'Twin') {
                    $roommate = Registration::where('room_id', $registration->room_id)
                                            ->where('id', '!=', $registration->id)
                                            ->first();
                    $info['roommate'] = $roommate?->participant?->user?->name ?? 'Belum ada rekan';
                }
            }
        }

        $this->participantInfo = $info;
        $this->confirmingRegistrationId = $registration->id;
        
        $this->showAlert('info', 'Peserta Valid! Silakan Konfirmasi.');
    }

    public function confirmAttendance()
    {
        if ($this->confirmingRegistrationId && $this->selected_agenda_id) {
            $agenda = Agenda::find($this->selected_agenda_id);
            
            AttendanceLog::create([
                'agenda_id' => $agenda->id,
                'gatekeeper_id' => $this->gatekeeper->id,
                'registration_id' => $this->confirmingRegistrationId,
                'status' => 'success',
                'notes' => null,
            ]);

            $this->confirmingRegistrationId = null;
            $this->showAlert('success', 'Berhasil Disimpan!');
        }
    }

    public function forceAccept()
    {
        if ($this->pendingRegistrationId && $this->selected_agenda_id) {
            $registration = Registration::with(['participant.user', 'room'])->find($this->pendingRegistrationId);
            $agenda = Agenda::find($this->selected_agenda_id);
            $this->recordAttendance($registration, $agenda, 'force_accepted', 'Manual override');
        }
    }

    private function recordAttendance($registration, $agenda, $status, $notes = null)
    {
        AttendanceLog::create([
            'agenda_id' => $agenda->id,
            'gatekeeper_id' => $this->gatekeeper->id,
            'registration_id' => $registration->id,
            'status' => $status,
            'notes' => $notes,
        ]);

        $info = [
            'name' => $registration->participant->user->name,
            'institution' => $registration->type_of_institution,
            'role' => $registration->role_at_summit,
        ];

        if ($agenda->type === 'check_in') {
            $info['room_type'] = $registration->room_type_preference;
            if ($registration->room) {
                $info['room_number'] = $registration->room->room_number;
                if ($info['room_type'] === 'Twin') {
                    $roommate = Registration::where('room_id', $registration->room_id)->where('id', '!=', $registration->id)->first();
                    $info['roommate'] = $roommate ? $roommate->participant->user->name : 'Belum ada rekan';
                }
            } else {
                $info['room_number'] = 'Belum di-assign';
            }
        }

        $this->participantInfo = $info;
        $this->showAlert('success', $status === 'force_accepted' ? 'Bypass Berhasil!' : 'Verifikasi Berhasil!');
    }

    private function promptForceAccept($registration, $reason)
    {
        $this->pendingRegistrationId = $registration->id;
        $this->showAlert('danger', "DITOLAK: {$reason}");
    }

    private function resetAlerts()
    {
        $this->scanStatus = '';
        $this->scanMessage = '';
        $this->participantInfo = null;
        $this->pendingRegistrationId = null;
    }

    private function showAlert($status, $message)
    {
        $this->scanStatus = $status;
        $this->scanMessage = $message;
    }
};
?>

<div class="min-h-screen bg-gray-900 text-white font-sans flex flex-col">
    <div class="bg-gray-800 p-4 border-b border-gray-700 shadow-md">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/favicon.png') }}" alt="GIHES Logo" class="w-10 h-10 object-contain">
                <div>
                    <h1 class="text-lg font-black text-[#C0A062] leading-tight">GATEKEEPER</h1>
                    <p class="text-xs text-gray-400">Petugas: <span class="text-white">{{ $gatekeeper->name }}</span></p>
                </div>
            </div>
            <div class="animate-pulse flex h-3 w-3">
                <span class="rounded-full bg-green-500 w-full h-full shadow-[0_0_8px_#22c55e]"></span>
            </div>
        </div>
    </div>

    <div class="flex-1 p-4 flex flex-col gap-4">
        
        <div class="bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-700">
            <label class="block text-sm font-bold text-gray-300 mb-2">Pilih Area Tugas / Agenda:</label>
            <select wire:model.live="selected_agenda_id" class="w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg p-3 focus:ring-[#C0A062] focus:border-[#C0A062]">
                <option value="" class="text-gray-400">-- Pilih Agenda Sebelum Scan --</option>
                @foreach($agendas as $agenda)
                    <option value="{{ $agenda->id }}" class="text-gray-900 bg-white">{{ $agenda->name }} ({{ strtoupper($agenda->type) }})</option>
                @endforeach
            </select>
        </div>

        @if($selected_agenda_id)
            <div wire:ignore class="bg-black rounded-xl overflow-hidden shadow-lg border-2 border-gray-700 relative">
                <div id="reader" width="100%"></div>
            </div>
            
            <div class="mt-2 bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-700 relative z-50">
                <label class="block text-xs font-bold text-gray-400 mb-2">Atau Cari Manual (Jika Barcode Gagal):</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.500ms="searchQuery" placeholder="Ketik nama peserta..." 
                           class="w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg p-3 focus:ring-[#C0A062]">
                    
                    @if(strlen($searchQuery) > 0)
                        <button wire:click="$set('searchQuery', '')" class="absolute right-3 top-3 text-gray-400 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    @endif
                </div>

                @if(count($searchResults) > 0)
                    <div class="absolute left-0 right-0 mt-2 bg-gray-900 border border-gray-600 rounded-lg shadow-2xl overflow-hidden z-[100]">
                        @foreach($searchResults as $result)
                            <button type="button" 
                                    wire:key="peserta-{{ $result->id }}" 
                                    wire:click.prevent="processManualSelect({{ $result->id }})" 
                                    class="w-full text-left p-4 border-b border-gray-700 hover:bg-gray-800 focus:bg-gray-700 transition cursor-pointer">
                                <span class="block font-bold text-white text-base">{{ $result->participant->user->name }}</span>
                                <span class="block text-xs text-gray-400 mt-1">{{ $result->participant->user->institution_name }} - {{ $result->participant->province }}</span>
                            </button>
                        @endforeach
                    </div>
                @elseif(strlen($searchQuery) >= 3)
                    <div class="absolute left-0 right-0 mt-2 bg-gray-900 border border-gray-600 rounded-lg p-3 text-center text-sm text-gray-400 z-[100]">
                        Tidak ada peserta dengan nama tersebut.
                    </div>
                @endif
            </div>

        @else
            <div class="bg-yellow-900/50 border border-yellow-700 text-yellow-300 p-4 rounded-xl text-center text-sm">
                Silakan pilih agenda tugas di atas untuk mengaktifkan kamera.
            </div>
        @endif
        @if($scanStatus)
            <div wire:key="alert-box-{{ now()->timestamp }}" class="mt-4 p-5 rounded-xl shadow-xl transition-all duration-300 
                {{ $scanStatus === 'info' ? 'bg-blue-900/90 border-2 border-blue-400' : '' }}
                {{ $scanStatus === 'success' ? 'bg-green-900/90 border-2 border-green-500' : '' }}
                {{ $scanStatus === 'warning' ? 'bg-yellow-900/90 border-2 border-yellow-500' : '' }}
                {{ $scanStatus === 'danger' ? 'bg-red-900/90 border-2 border-red-500' : '' }}
            ">
                
                <div class="flex items-center gap-3 mb-3">
                    @if($scanStatus === 'success')
                        <svg class="w-8 h-8 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($scanStatus === 'warning')
                        <svg class="w-8 h-8 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    @elseif($scanStatus === 'info')
                        <svg class="w-8 h-8 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @else
                        <svg class="w-8 h-8 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                    
                    <p class="font-bold text-lg 
                        {{ $scanStatus === 'success' ? 'text-green-300' : '' }}
                        {{ $scanStatus === 'warning' ? 'text-yellow-300' : '' }}
                        {{ $scanStatus === 'info' ? 'text-blue-300' : '' }}
                        {{ $scanStatus === 'danger' ? 'text-red-300' : '' }}
                    ">
                        {{ $scanMessage }}
                    </p>
                </div>

                @if($participantInfo)
                    <div class="bg-black/40 rounded-lg p-3 text-sm space-y-2 mt-4 border border-white/10">
                        <p class="text-gray-300">Nama: <span class="font-bold text-white">{{ $participantInfo['name'] }}</span></p>
                        <p class="text-gray-300">Instansi: <span class="font-bold text-white">{{ $participantInfo['institution'] }}</span></p>
                        <p class="text-gray-300">Tipe: <span class="font-bold text-white">{{ $participantInfo['role'] }}</span></p>
                        
                        @if(isset($participantInfo['room_number']))
                            <div class="mt-3 pt-3 border-t border-white/20">
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Informasi Kamar</p>
                                <p class="text-gray-300">Nomor Kamar: <span class="font-bold text-[#C0A062] text-xl">{{ $participantInfo['room_number'] }}</span></p>
                                @if(isset($participantInfo['roommate']))
                                    <p class="text-gray-300">Sekamar dengan: <span class="font-bold text-white">{{ $participantInfo['roommate'] }}</span></p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mt-4 space-y-2">
                    
                    @if($scanStatus === 'info' && $confirmingRegistrationId)
                        <button wire:click="confirmAttendance" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-lg flex items-center justify-center gap-2 transition cursor-pointer shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            KONFIRMASI SEKARANG
                        </button>
                    @endif

                    @if($scanStatus === 'danger' && $pendingRegistrationId)
                        <button wire:click="forceAccept" onclick="confirm('Yakin bypass keamanan?') || event.stopImmediatePropagation()" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-4 rounded-lg flex items-center justify-center gap-2 transition cursor-pointer shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            FORCE ACCEPT
                        </button>
                    @endif

                    @if($scanStatus !== 'success')
                        <button wire:click="$set('scanStatus', '')" class="w-full bg-transparent border border-gray-500 text-gray-300 hover:bg-gray-800 font-bold py-3 px-4 rounded-lg flex items-center justify-center transition cursor-pointer">
                            Batal / Scan Ulang
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    @script
    <script>
        let html5QrcodeScanner = null;
        let isScanning = false;

        function startScanner() {
            let readerEl = document.getElementById('reader');
            if (!readerEl || html5QrcodeScanner) return;

            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
                }, false);

            html5QrcodeScanner.render(onScanSuccess);
        }

        function onScanSuccess(decodedText, decodedResult) {
            if(isScanning) return; 
            isScanning = true;
            
            let audio = new Audio('data:audio/mp3;base64,//OExAAAAANIAAAAAExBTUUzLjEwMKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq');
            audio.play().catch(e => console.log('Audio autoplay blocked'));

            $wire.processScan(decodedText).then(() => {
                setTimeout(() => { isScanning = false; }, 2500);
            });
        }

        setTimeout(() => { startScanner(); }, 500);

        Livewire.hook('morph.updated', (el, component) => {
            setTimeout(() => { startScanner(); }, 100);
        });
    </script>
    @endscript
    
    <style>
        #reader { border: none !important; }
        
        #reader select {
            color: #111827 !important; 
            background-color: #f3f4f6 !important;
            padding: 6px !important;
            border-radius: 6px !important;
            border: 1px solid #d1d5db !important;
            width: 100%;
            max-width: 300px;
            margin-bottom: 10px;
        }

        #reader button {
            background-color: #C0A062 !important; 
            color: #12241C !important; 
            font-weight: 900 !important;
            text-transform: uppercase !important;
            border: none !important;
            padding: 10px 20px !important;
            border-radius: 8px !important;
            margin: 10px 0 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        #reader a { color: #C0A062 !important; text-decoration: none; }
        #reader__dashboard_section_csr span, 
        #reader__dashboard_section_csr div { color: #d1d5db !important; }
    </style>
</div>