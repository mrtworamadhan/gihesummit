<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::app')] class extends Component
{
    public $user;
    public $status; // 'incomplete', 'pending_payment', 'paid'
    public $progressPercentage;
    
    public function mount()
    {
        
        $this->user = Auth::user();

        
        $participant = $this->user->participant;
        $registration = $participant?->registration;
        $payment = $registration?->payment;

        if (!$participant) {
            $this->status = 'incomplete';
            $this->progressPercentage = 25; 
            
        } elseif (!$registration) {
            $this->status = 'incomplete';
            $this->progressPercentage = 50; 
            
        } elseif (!$payment) {
            $this->status = 'incomplete';
            $this->progressPercentage = 75; 
            
        } elseif ($payment->payment_status !== 'paid') {
            $this->status = 'pending_payment';
            $this->progressPercentage = 90; 
            
        } else {
            $this->status = 'paid';
            $this->progressPercentage = 100;
        }
    }
};
?>

<div class="max-w-6xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">Dashboard Overview</h1>
            <p class="text-gray-500 mt-1">Welcome to your GIHES 2026 Participant Portal.</p>
        </div>

        <div>
            @if($status === 'incomplete')
                <span class="bg-red-100 text-red-800 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider border border-red-200">
                    Registration Incomplete ({{ $progressPercentage }}%)
                </span>

            @elseif($status === 'pending_payment')
                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider border border-yellow-200">
                    Awaiting Payment Verification
                </span>

            @elseif($status === 'paid' && $user->participant?->registration?->is_waiting_list)
                <span class="bg-purple-100 text-purple-800 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider border border-purple-200">
                    Paid - Waiting List
                </span>

            @elseif($status === 'paid')
                <span class="bg-green-100 text-green-800 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider border border-green-200">
                    Fully Verified & Confirmed
                </span>

            @endif
        </div>
    </div>

    @if($status === 'pending_payment')
    <div class="bg-white rounded-xl shadow-lg border-l-4 border-yellow-500 p-6 md:p-8 relative overflow-hidden">
        @php
            $sisaKuota = \App\Models\Registration::getRemainingQuota();
        @endphp

        @if($sisaKuota > 0 && $sisaKuota <= 50)
            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-800 p-4 rounded shadow-sm mb-6 flex items-center justify-between">
                <div>
                    <p class="font-bold">Hurry up! Limited Seats Available.</p>
                    <p class="text-sm">Main quota is almost full (Only {{ $sisaKuota }} seats left). Complete your payment immediately to secure your spot. First pay, first serve!</p>
                </div>
            </div>
        @elseif($sisaKuota == 0)
            <div class="bg-red-100 border-l-4 border-red-600 text-red-900 p-4 rounded shadow-sm mb-6">
                <p class="font-black uppercase tracking-wider text-red-700">Main Quota (300) is FULL!</p>
                <p class="text-sm mt-1">Registration is still open, but you will be placed on the <strong>Waiting List</strong>. We will notify you if a seat becomes available. You may proceed with payment to secure your priority on the waitlist (fully refundable if no seat opens).</p>
            </div>
        @endif
        <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between border-b border-gray-200 pb-6 mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">Awaiting Payment Confirmation</span>
                </div>
                <h2 class="text-2xl font-bold text-[#1B1B1B] mb-2">Your Order Registration is Pending</h2>
                <p class="text-gray-600">Please complete the transfer and confirm with our admin.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                <a href="{{ route('panel.wizard') }}" class="flex items-center justify-center gap-2 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 px-6 py-3 rounded-sm font-bold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Order
                </a>
                
                @php
                    $payment = $user->participant?->registration?->payment;
                    $currency = $payment?->currency ?? 'IDR';
                    $amount = $payment?->final_amount ?? 0;
                    $amountText = $currency === 'USD' ? number_format($amount, 2) : number_format($amount, 0, ',', '.');
                    
                    $adminWaNumber = "6281234567890";
                    $pesanWA = "Hello GIHES Admin,\n\nI am *" . $user->name . "* from *" . $user->institution_name . "*. I have completed the registration process.\n\nTotal Amount: *" . $currency . " " . $amountText . "*\n\nPlease find my transfer receipt attached. Kindly assist with the verification.";
                    $waLink = "https://wa.me/" . $adminWaNumber . "?text=" . urlencode($pesanWA);
                @endphp
                <a href="{{ $waLink }}" target="_blank" class="flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1ebe5d] text-white px-6 py-3 rounded-sm font-bold shadow-lg transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Re-Confirm Payment
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ copiedAmount: false, copiedRek: false }">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-bold mb-1">Total Amount to Transfer</p>
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black text-[#C0A062]">{{ $currency }} {{ $amountText }}</span>
                    <button type="button" @click="navigator.clipboard.writeText('{{ $currency === 'USD' ? $amount : (int)$amount }}'); copiedAmount = true; setTimeout(() => copiedAmount = false, 2000)" class="text-gray-400 hover:text-[#5A6446]" title="Copy Amount">
                        <svg x-show="!copiedAmount" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <svg x-show="copiedAmount" x-cloak class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 border border-gray-200 rounded">
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Bank Transfer (BSI)</p>
                <div class="flex items-center gap-3">
                    <span class="font-bold text-gray-900 tracking-widest text-lg">7353689268</span>
                    <button type="button" @click="navigator.clipboard.writeText('7353689268'); copiedRek = true; setTimeout(() => copiedRek = false, 2000)" class="text-gray-400 hover:text-[#5A6446]">
                        <svg x-show="!copiedRek" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <svg x-show="copiedRek" x-cloak class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">a/n Forum Pesantren Alumni Gontor</p>
            </div>
        </div>
    </div>
    @endif

    @if($status === 'paid')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-b from-[#12241C] to-[#2B2D26] rounded-xl shadow-xl p-6 text-center border-t-4 border-[#C0A062]">
                <h2 class="text-[#C0A062] font-bold tracking-widest uppercase text-xs mb-1">GIHES 2026 Official Pass</h2>
                <div class="bg-white p-3 rounded-lg mx-auto w-40 h-40 flex items-center justify-center shadow-inner my-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $user->id }}-GIHES2026" alt="QR Code" class="w-full h-full object-contain">
                </div>
                <h3 class="text-xl font-black text-white mb-1">{{ $user->name }}</h3>
                <p class="text-[#C0A062] text-xs font-bold uppercase">{{ $user->institution_name }}</p>
                <span class="inline-block mt-4 bg-green-500/20 text-green-400 text-[10px] font-bold px-3 py-1 rounded-full border border-green-500/50 uppercase">
                    Verified Delegate
                </span>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-[#1B1B1B] mb-4 border-b pb-3">Upcoming Agenda</h3>
            <div class="relative border-l-2 border-gray-200 ml-3 space-y-6">
                <div class="relative pl-6">
                    <div class="absolute w-3 h-3 bg-[#C0A062] rounded-full -left-[7px] top-1.5 border-2 border-white"></div>
                    <p class="text-xs text-[#C0A062] font-bold">Day 1 - 09:00 AM</p>
                    <h4 class="text-base font-bold text-[#1B1B1B]">Opening Ceremony & Keynote Address</h4>
                    <p class="text-xs text-gray-500 mt-1">Ballroom A, Hotel Borobudur Jakarta</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @php
        $participant = $user->participant;
        $registration = $participant?->registration;
        $isPaid = ($status === 'paid');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative overflow-hidden">
            <div class="flex justify-between items-start mb-4 border-b pb-3">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <span class="w-7 h-7 rounded bg-[#12241C] text-white flex items-center justify-center text-xs">1</span>
                    Institution & Profile
                </h3>
                @if(!$participant || !$participant->position_title)
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider">Draft</span>
                @elseif(!$isPaid)
                    <span class="bg-yellow-50 text-yellow-700 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider border border-yellow-200">Draft</span>
                @endif
            </div>

            @if($participant && $participant->position_title)
                <ul class="space-y-2 text-xs text-gray-600">
                    <li class="flex justify-between"><span>Position:</span> <strong class="text-gray-900">{{ $participant->position_title }}</strong></li>
                    <li class="flex justify-between"><span>Type:</span> <strong class="text-gray-900">{{ $participant->type_of_institution }}</strong></li>
                    <li class="flex justify-between"><span>Province:</span> <strong class="text-gray-900">{{ $participant->province }}</strong></li>
                    <li class="flex justify-between"><span>Role at Summit:</span> <strong class="text-gray-900">{{ $registration->role_at_summit ?? '-' }}</strong></li>
                </ul>
            @else
                <p class="text-xs text-gray-400 italic">Details not completed yet.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative overflow-hidden">
            <div class="flex justify-between items-start mb-4 border-b pb-3">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <span class="w-7 h-7 rounded bg-[#12241C] text-white flex items-center justify-center text-xs">2</span>
                    Accommodation & Logistics
                </h3>
                @if(!$registration || !$registration->room_id)
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider">Draft</span>
                @elseif(!$isPaid)
                    <span class="bg-yellow-50 text-yellow-700 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider border border-yellow-200">Draft</span>
                @endif
            </div>

            @if($registration && $registration->room_id)
                <ul class="space-y-2 text-xs text-gray-600">
                    <li class="flex justify-between"><span>Room Type:</span> <strong class="text-gray-900">{{ $registration->room_type }} Room</strong></li>
                    <li class="flex justify-between"><span>Room Number:</span> <strong class="text-gray-900">{{ $registration->room?->room_number ?? '-' }}</strong></li>
                    <li class="flex justify-between"><span>Departure From:</span> <strong class="text-gray-900">{{ $registration->departure_city_country }}</strong></li>
                </ul>
            @else
                <p class="text-xs text-gray-400 italic">Accommodation not selected yet.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative overflow-hidden">
            <div class="flex justify-between items-start mb-4 border-b pb-3">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <span class="w-7 h-7 rounded bg-[#12241C] text-white flex items-center justify-center text-xs">3</span>
                    Additional Classes & Files
                </h3>
                @if(!$registration || !$registration->mandate_letter_path)
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider">Draft</span>
                @elseif(!$isPaid)
                    <span class="bg-yellow-50 text-yellow-700 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider border border-yellow-200">Draft</span>
                @endif
            </div>

            @if($registration)
                <div class="space-y-3 text-xs text-gray-600">
                    <div>
                        <span class="text-gray-400 block mb-1">Selected Classes:</span>
                        <div class="flex flex-wrap gap-1">
                            @forelse($registration->additionalClasses as $cls)
                                <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded font-medium">{{ $cls->name }}</span>
                            @empty
                                <span class="text-gray-400 italic">No additional classes selected.</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="pt-2 border-t flex justify-between items-center">
                        <span>Mandate Letter:</span>
                        @if($registration->mandate_letter_path)
                            <span class="text-green-600 font-bold flex items-center gap-1">Uploaded ✓</span>
                        @else
                            <span class="text-red-500 font-bold">Missing</span>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-xs text-gray-400 italic">Not configured yet.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative overflow-hidden">
            <div class="flex justify-between items-start mb-4 border-b pb-3">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <span class="w-7 h-7 rounded bg-[#12241C] text-white flex items-center justify-center text-xs">4</span>
                    Payment Summary
                </h3>
                @if(!$registration?->payment)
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider">Unpaid</span>
                @elseif($registration->payment->payment_status === 'paid')
                    
                @else
                    <span class="bg-yellow-50 text-yellow-700 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider border border-yellow-200">Pending</span>
                @endif
            </div>

            @if($registration?->payment)
                @php
                    $pay = $registration->payment;
                    $curr = $pay->currency;
                    $amtText = $curr === 'USD' ? number_format($pay->final_amount, 2) : number_format($pay->final_amount, 0, ',', '.');
                @endphp
                <ul class="space-y-2 text-xs text-gray-600">
                    <li class="flex justify-between"><span>Category:</span> <strong class="text-gray-900">{{ $pay->registration_category }}</strong></li>
                    <li class="flex justify-between"><span>Total Amount:</span> <strong class="text-[#C0A062] font-black text-sm">{{ $curr }} {{ $amtText }}</strong></li>
                    <li class="flex justify-between"><span>Status:</span> 
                        <strong class="uppercase {{ $pay->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ str_replace('_', ' ', $pay->payment_status) }}
                        </strong>
                    </li>
                </ul>
            @else
                <p class="text-xs text-gray-400 italic">Invoice not generated yet. Complete the wizard steps.</p>
            @endif
        </div>

    </div>

    @if($status !== 'paid')
    <div class="text-center pt-4">
        <a href="{{ route('panel.wizard') }}" class="inline-flex items-center gap-2 bg-[#12241C] hover:bg-[#1a3328] text-white px-8 py-4 rounded-sm font-black text-sm uppercase tracking-widest shadow-lg transition-all">
            <span>Continue / Edit Registration Wizard</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
    @endif

</div>