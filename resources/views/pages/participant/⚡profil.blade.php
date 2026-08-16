<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::app')] class extends Component
{
    public $user;
    public $participant;
    public $registration;

    public function mount()
    {
        $this->user = Auth::user();
        $this->participant = $this->user->participant;
        $this->registration = $this->participant?->registration;
    }
};
?>

<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('participant.dashboard') }}" class="text-sm text-gray-500 hover:text-[#C0A062] flex items-center gap-1 mb-2 transition-colors">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">Account & Institution Profile</h1>
            <p class="text-gray-500 mt-1">Comprehensive profile details registered for GIHES 2026.</p>
        </div>
        
        <a href="{{ route('panel.wizard') }}" class="bg-[#12241C] hover:bg-[#1a3328] text-white px-5 py-2.5 rounded-sm text-xs font-bold uppercase tracking-wider shadow transition-all">
            Edit Information in Wizard
        </a>
    </div>

    @php
        $registration = $participant?->registration;
    @endphp

    <!-- 1. Personal & Contact Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-900 text-base">Personal & Contact Information</h3>
        </div>
        <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Full Name</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">{{ $user->name }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Email Address</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">{{ $user->email }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">WhatsApp Number</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">+{{ $user->whatsapp }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Nationality</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">{{ $user->nationality }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-900 text-base">Institution & Address Profile</h3>
        </div>
        @if($participant)
        <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Institution Name</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">{{ $user->institution_name }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Type of Institution</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">{{ $participant->type_of_institution ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Position / Title</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">{{ $participant->position_title ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Province / State</span>
                <span class="text-gray-900 font-bold text-base mt-1 block">{{ $participant->province ?? '-' }}</span>
            </div>
            <div class="md:col-span-2">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Full Institution Address</span>
                <span class="text-gray-900 font-medium mt-1 block leading-relaxed">{{ $participant->institution_address ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Website / Social Media</span>
                <span class="text-gray-900 font-medium mt-1 block">{{ $participant->website_social_media ?? 'Not provided' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Institution Scale</span>
                <span class="text-gray-900 font-medium mt-1 block">{{ $participant->institution_scale ?? 'Not provided' }}</span>
            </div>
        </div>
        @else
        <div class="p-8 text-center text-gray-400 italic text-sm">Institution details have not been completed yet.</div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-900 text-base">Summit Participation & Declaration</h3>
        </div>
        @if($registration)
        <div class="p-6 md:p-8 space-y-6 text-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Role at Summit</span>
                    <span class="text-gray-900 font-bold text-base mt-1 block">{{ $registration->role_at_summit ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Showcase Category</span>
                    <span class="text-gray-900 font-bold text-base mt-1 block">{{ $registration->showcase_category ?? 'N/A' }}</span>
                </div>
            </div>

            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Preferred Working Groups</span>
                <div class="flex flex-wrap gap-2">
                    @php
                        $wg = is_array($registration->preferred_working_group) ? $registration->preferred_working_group : json_decode($registration->preferred_working_group, true);
                    @endphp
                    @forelse($wg ?? [] as $group)
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-3 py-1 rounded border border-gray-200">{{ $group }}</span>
                    @empty
                        <span class="text-gray-400 italic">No working group selected.</span>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Willingness to Co-sign Declaration</span>
                    <span class="text-gray-900 font-medium mt-1 block">{{ $registration->willingness_to_cosign_declaration ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Mandate / Assignment Letter</span>
                    @if($registration->mandate_letter_path)
                        <a href="{{ asset('storage/' . $registration->mandate_letter_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-600 hover:underline font-bold mt-1">
                            <span>View Uploaded Document</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    @else
                        <span class="text-red-500 font-medium mt-1 block">Not uploaded</span>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="p-8 text-center text-gray-400 italic text-sm">Participation data has not been filled out yet.</div>
        @endif
    </div>
</div>