<?php

use Livewire\Component;
use App\Models\Agenda;
use App\Models\AttendanceLog;
use App\Models\Registration;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

new #[Layout('layouts::monitor')] class extends Component
{
    #[Computed]
    public function agendas()
    {
        return Agenda::withCount(['attendanceLogs' => function ($query) {
            $query->whereIn('status', ['success', 'force_accepted']);
        }])->where('is_active', true)->get();
    }

    #[Computed]
    public function recentLogs()
    {
        return AttendanceLog::with(['registration.participant.user', 'agenda', 'gatekeeper'])
                        ->whereIn('status', ['success', 'force_accepted'])
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();
    }

    #[Computed]
    public function forceAcceptCount()
    {
        return AttendanceLog::where('status', 'force_accepted')->count();
    }

    #[Computed]
    public function totalPeserta()
    {
        return Registration::count();
    }
};
?>

<div wire:poll.3s class="p-6 h-screen flex flex-col">
    
    <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4 shrink-0">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/favicon.png') }}" class="h-12 object-contain" alt="GIHES">
            <div>
                <h1 class="text-3xl font-black text-[#C0A062] tracking-wider leading-tight">COMMAND CENTER</h1>
                <p class="text-gray-400 text-sm tracking-widest uppercase">Live Attendance Monitoring</p>
            </div>
        </div>
        <div class="text-right">
            <div class="text-3xl font-mono font-bold text-white tracking-widest">{{ now()->format('H:i:s') }}</div>
            <div class="text-sm text-green-400 flex items-center justify-end gap-2 mt-1">
                <span class="animate-pulse rounded-full bg-green-500 w-2 h-2 block"></span> Live Sync Active
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 flex-1 overflow-hidden">
        
        <div class="col-span-8 flex flex-col gap-6 overflow-y-auto pr-2 pb-6">
            <div class="grid grid-cols-2 gap-4">
                @foreach($this->agendas as $agenda)
                <div class="bg-gray-800 rounded-xl p-5 border-t-4 {{ $agenda->type === 'check_in' ? 'border-[#C0A062]' : 'border-blue-500' }} shadow-xl">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1">{{ $agenda->name }}</h3>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Tipe: {{ $agenda->type }}</p>
                        </div>
                        <div class="bg-gray-900 rounded-lg p-2">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-black text-white">{{ number_format($agenda->attendance_logs_count) }}</span>
                        <span class="text-gray-400 text-sm mb-1">Orang Masuk</span>
                    </div>
                </div>
                @endforeach
            </div>

            @if($this->forceAcceptCount > 0)
            <div class="bg-red-900/30 border border-red-500/50 rounded-xl p-5 flex items-center gap-4 mt-2 shadow-lg">
                <div class="bg-red-500/20 text-red-400 p-3 rounded-full shrink-0">
                    <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h4 class="text-red-400 font-bold text-lg">SECURITY ALERT</h4>
                    <p class="text-sm text-gray-300">Terdapat <strong>{{ $forceAcceptCount }} peserta</strong> yang dimasukkan melewati batas keamanan sistem (Jalur Force Accept/Bypass). Silakan cek log untuk validasi silang.</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-span-4 bg-gray-800 rounded-xl border border-gray-700 shadow-xl flex flex-col h-[calc(100vh-120px)]">
            <div class="p-4 border-b border-gray-700 bg-gray-900/50 rounded-t-xl flex justify-between items-center shrink-0">
                <h2 class="font-bold text-[#C0A062]">🔴 LIVE TRAFFIC FEED</h2>
                <span class="text-xs bg-gray-700 px-2 py-1 rounded text-gray-300">Last 10 Scans</span>
            </div>
            
            <div class="flex-1 p-4 overflow-y-auto space-y-3">
                @forelse($this->recentLogs as $log)
                    <div class="bg-gray-900 p-4 rounded-lg border-l-4 {{ $log->status === 'success' ? 'border-green-500' : 'border-red-500' }} shadow animate-[fadeIn_0.5s_ease-out]">
                        <div class="flex justify-between items-start mb-1">
                            <p class="font-bold text-white text-sm truncate pr-2">
                                {{ $log->registration->participant?->user?->name ?? 'Unknown User' }}
                            </p>
                            <span class="text-[10px] text-gray-400 whitespace-nowrap bg-gray-800 px-2 py-0.5 rounded">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-400 flex justify-between">
                            <span>Pos: <strong class="text-gray-200">{{ $log->agenda->name }}</strong></span>
                        </p>
                        <p class="text-[10px] text-gray-500 mt-2 flex justify-between border-t border-gray-800 pt-2">
                            <span>Scan by: {{ $log->gatekeeper->name ?? '-' }}</span>
                            @if($log->status === 'force_accepted')
                                <span class="text-red-400 font-bold">BYPASSED</span>
                            @endif
                        </p>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-gray-500 space-y-3">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm">Belum ada pergerakan pintu.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>