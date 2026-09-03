<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::app')] class extends Component
{
    public $user;
    public $participant;
    public $registration;
    public $room;

    public function mount()
    {
        $this->user = Auth::user();
        $this->participant = $this->user->participant;
        $this->registration = $this->participant?->registration;
        $this->room = $this->registration?->room;
    }
};
?>

<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('participant.dashboard') }}" class="text-sm text-gray-500 hover:text-[#C0A062] flex items-center gap-1 mb-2 transition-colors">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">Accommodation & Logistics</h1>
            <p class="text-gray-500 mt-1">Your hotel room allocation and travel itinerary details.</p>
        </div>
        
        <a href="{{ route('panel.wizard') }}" class="bg-[#12241C] hover:bg-[#1a3328] text-white px-5 py-2.5 rounded-sm text-xs font-bold uppercase tracking-wider shadow transition-all">
            Edit Logistics in Wizard
        </a>
    </div>

    @if($registration && $registration->room)
    <div class="bg-gradient-to-br from-[#12241C] to-[#2B2D26] text-white rounded-xl shadow-xl p-6 md:p-8 relative overflow-hidden border-t-4 border-[#C0A062]">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
            <div>
                <span class="bg-[#C0A062]/20 text-[#C0A062] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest border border-[#C0A062]/30">
                    Assigned Room
                </span>
                <h2 class="text-3xl font-black mt-3 mb-1">Room {{ $room->room_number }}</h2>
                <p class="text-gray-300 text-sm">{{ $room->type }} Room Accommodation (GIHES Official Hotel Partner)</p>
            </div>

            @php
                $adminWaNumber = "6281335444683"; 
                $pesanWA = "Hello GIHES Admin,\n\nI am *" . $user->name . "* from *" . $user->institution_name . "* (Room: " . $room->room_number . ").\n\nI would like to request a room change / accommodation adjustment. Kindly assist me with the approval and procedure.";
                $waLink = "https://wa.me/" . $adminWaNumber . "?text=" . urlencode($pesanWA);
            @endphp
            <a href="{{ $waLink }}" target="_blank" class="bg-white hover:bg-gray-100 text-[#12241C] px-6 py-3 rounded-sm text-xs font-black uppercase tracking-widest shadow-lg flex items-center gap-2 transition-transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 text-[#C0A062]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Request Room Change (Admin Approval)
            </a>
        </div>

        @if($room->type === 'Twin')
        <div class="mt-8 pt-6 border-t border-gray-700/60">
            <p class="text-xs text-[#C0A062] font-bold uppercase tracking-wider mb-2">Roommate Information</p>
            @php
                $roommates = $room->registrations()->where('id', '!=', $registration->id)->with('participant.user')->get();
            @endphp
            @if($roommates->count() > 0)
                <div class="flex items-center gap-3 bg-black/20 p-3 rounded border border-gray-700">
                    <div class="w-8 h-8 rounded-full bg-[#C0A062] text-[#12241C] font-bold flex items-center justify-center text-sm">
                        {{ substr($roommates->first()->participant->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold">{{ $roommates->first()->participant->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $roommates->first()->participant->user->institution_name }}</p>
                    </div>
                </div>
            @else
                <p class="text-xs text-gray-400 italic">Looking for a roommate / Bed is currently available.</p>
            @endif
        </div>
        @endif
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-6 rounded-xl text-center">
        <p class="font-bold">Accommodation not selected yet.</p>
        <p class="text-xs mt-1">Please complete your registration wizard to choose your room.</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-900 text-base">Travel & Itinerary Schedule</h3>
        </div>
        @if($registration)
        <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Departure City / Country</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">{{ $registration->departure_city_country ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Estimated Arrival</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">
                    {{ $registration->estimated_arrival ? \Carbon\Carbon::parse($registration->estimated_arrival)->format('d M Y, H:i') : '-' }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Estimated Departure</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">
                    {{ $registration->estimated_departure ? \Carbon\Carbon::parse($registration->estimated_departure)->format('d M Y, H:i') : '-' }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Accommodation Assistance</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">
                    {{ $registration->needs_accommodation_assist ? 'Yes, requested' : 'No, self-arranged' }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Dietary Restrictions</span>
                <span class="text-gray-900 font-medium mt-1 block">{{ $registration->dietary_restrictions ?? 'None specified (Halal standard)' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Accessibility Needs</span>
                <span class="text-gray-900 font-medium mt-1 block">{{ $registration->accessibility_needs ?? 'None' }}</span>
            </div>
        </div>
        @else
        <div class="p-8 text-center text-gray-400 italic text-sm">Logistics data has not been filled out yet.</div>
        @endif
    </div>
</div>