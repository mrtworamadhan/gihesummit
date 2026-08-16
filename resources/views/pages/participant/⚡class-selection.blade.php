<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\AdditionalClass;

new #[Layout('layouts::app')] class extends Component
{
    public $user;
    public $registration;
    public $assignedClasses = [];
    public $availableClasses = [];
    public $paymentStatus = 'unpaid';

    public function mount()
    {
        $this->user = Auth::user();
        $this->registration = $this->user->participant?->registration;
        
        if ($this->registration) {
            $this->paymentStatus = $this->registration->payment?->payment_status ?? 'unpaid';
            
            $this->assignedClasses = $this->registration->additionalClasses;
            
            $assignedClassIds = $this->assignedClasses->pluck('id')->toArray();
            $this->availableClasses = AdditionalClass::where('is_active', true)
                                        ->whereNotIn('id', $assignedClassIds)
                                        ->get();
        } else {
            $this->availableClasses = AdditionalClass::where('is_active', true)->get();
            $this->assignedClasses = collect();
        }
    }
    
};
?>

<div class="max-w-5xl mx-auto space-y-8 pb-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <a href="{{ route('participant.dashboard') }}" class="text-sm text-gray-500 hover:text-[#C0A062] flex items-center gap-1 mb-2 transition-colors">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">Programs & Classes</h1>
            <p class="text-gray-500 mt-1">Manage your enrolled sessions and explore additional opportunities.</p>
        </div>
    </div>

    <div>
        <h2 class="text-xl font-black text-[#12241C] border-b-2 border-[#C0A062] inline-block pb-1 mb-6 uppercase tracking-wider">
            Assigned Classes
        </h2>

        @if(count($assignedClasses) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($assignedClasses as $class)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                    <div class="bg-[#12241C] p-4 flex justify-between items-center">
                        <span class="bg-[#C0A062] text-[#12241C] text-[10px] font-black px-2 py-1 rounded uppercase tracking-widest">Enrolled</span>
                        <svg class="w-5 h-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $class->name }}</h3>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed flex-1">{{ $class->description }}</p>
                        
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 space-y-3">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-[#C0A062] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div>
                                    <p class="text-xs font-bold text-gray-900 uppercase">Schedule</p>
                                    <p class="text-sm text-gray-600">Day 2 - 14:00 PM (WIB)</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-[#C0A062] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <div>
                                    <p class="text-xs font-bold text-gray-900 uppercase">Location</p>
                                    <p class="text-sm text-gray-600">Meeting Room B, 2nd Floor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                <p class="text-gray-500 font-medium">You haven't enrolled in any additional classes yet.</p>
            </div>
        @endif
    </div>

    <div class="pt-8">
        <h2 class="text-xl font-black text-[#12241C] border-b-2 border-gray-300 inline-block pb-1 mb-6 uppercase tracking-wider">
            Available Class Options
        </h2>

        @if(count($availableClasses) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach($availableClasses as $option)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col group hover:border-[#C0A062] transition-colors">
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-[#5A6446] transition-colors leading-tight">{{ $option->name }}</h3>
                        </div>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed flex-1">{{ $option->description }}</p>
                        
                        <div class="flex items-end justify-between mt-auto">
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Investment</span>
                                @if($user->nationality !== 'Indonesia' ? $option->price_usd : $option->price_idr > 0)
                                    <span class="text-lg font-black text-[#12241C]">
                                        {{ $user->nationality !== 'Indonesia' ? 'USD ' . number_format($option->price_usd, 2) : 'Rp ' . number_format($option->price_idr, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-sm font-black text-green-600 uppercase tracking-wider">Free</span>
                                @endif
                            </div>

                            @if($paymentStatus === 'paid')
                                @php
                                    $waLink = "https://wa.me/6281234567890?text=" . urlencode("Hello GIHES Admin, I am *" . $user->name . "*. I have already paid my main registration, but I would like to ADD the following class:\n\n*[" . $option->name . "]*\n\nPlease let me know the procedure to secure my seat.");
                                @endphp
                                <a href="{{ $waLink }}" target="_blank" class="bg-[#12241C] text-white px-4 py-2 rounded text-xs font-bold hover:bg-[#C0A062] transition-colors shadow">
                                    Request to Add
                                </a>
                            @else
                                <a href="{{ route('panel.wizard') }}" class="bg-[#12241C] text-white px-4 py-2 rounded text-xs font-bold hover:bg-[#C0A062] transition-colors shadow">
                                    Add in Wizard
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                <p class="text-gray-500 font-medium">No other classes are available at the moment.</p>
            </div>
        @endif
    </div>

</div>