<?php

use Livewire\Component;
use App\Models\Certificate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::app')] class extends Component
{
    public $user;
    public $availableCertificates = [];

    public function mount()
    {
        $this->user = Auth::user();
        $registration = $this->user->participant?->registration;

        if ($registration && $registration->payment?->payment_status === 'paid') {
            
            $mainCerts = Certificate::where('type', 'main')
                                    ->where('is_published', true)
                                    ->get();

            $classIds = $registration->additionalClasses->pluck('id')->toArray();
            $classCerts = Certificate::where('type', 'class')
                                     ->whereIn('additional_class_id', $classIds)
                                     ->where('is_published', true)
                                     ->get();

            $this->availableCertificates = $mainCerts->concat($classCerts);
        }
    }

    public function downloadCertificate($certificateId)
    {
        session()->flash('message', 'Processing your certificate... (Image generator will be implemented here)');
    }
};
?>

<div class="max-w-4xl mx-auto space-y-8 pb-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <a href="{{ route('participant.dashboard') }}" class="text-sm text-gray-500 hover:text-[#C0A062] flex items-center gap-1 mb-2 transition-colors">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">E-Certificates</h1>
            <p class="text-gray-500 mt-1">Download your official verified certificates here.</p>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="p-4 bg-green-50 text-green-700 border border-green-200 rounded-md font-bold mb-6">
            {{ session('message') }}
        </div>
    @endif

    @if(count($availableCertificates) > 0)
        <div class="space-y-4">
            @foreach($availableCertificates as $cert)
            <div class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:shadow-md transition-shadow">
                
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-[#12241C] rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 text-[#C0A062]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $cert->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Issued to: <strong class="text-gray-800 uppercase">{{ $user->name }}</strong>
                        </p>
                        @if($cert->type === 'main')
                            <span class="inline-block mt-2 bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200 uppercase tracking-wider">Official Summit Certificate</span>
                        @else
                            <span class="inline-block mt-2 bg-purple-50 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded border border-purple-200 uppercase tracking-wider">Special Program</span>
                        @endif
                    </div>
                </div>

                <button wire:click="downloadCertificate({{ $cert->id }})" class="w-full md:w-auto bg-[#C0A062] hover:bg-[#a3854d] text-[#12241C] px-6 py-3 rounded-sm text-sm font-black uppercase tracking-widest shadow-lg flex items-center justify-center gap-2 transition-transform hover:-translate-y-0.5 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download PDF
                </button>
                
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-12 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <p class="text-gray-500 font-medium">Certificates are not available yet.</p>
            <p class="text-xs text-gray-400 mt-1">They will be published here after the event concludes and payment is verified.</p>
        </div>
    @endif

</div>