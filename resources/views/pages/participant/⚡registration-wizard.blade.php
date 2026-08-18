<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Registration;
use App\Models\AdditionalClass;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::app')] class extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 4;

    public $is_international = false;

    // STEP 1
    public $type_of_institution, $institution_address, $province, $website_social_media, $institution_scale, $position_title;
    public $role_at_summit, $showcase_category, $willingness_to_cosign_declaration;
    public $preferred_working_group = [];
    public $other_position_title;

    // STEP 2
    public $departure_city_country, $estimated_arrival, $estimated_departure;
    public $tour_guide_needed = false; 
    public $room_type; 
    public $selected_room_type;
    public $needs_accommodation_assist = 1, $requires_visa_letter = 0;
    public $dietary_restrictions, $accessibility_needs;

    // STEP 3
    public $mandate_letter; 
    public $consent_data_use = true;
    public $selected_classes = [];

    // STEP 4
    public $payment_currency, $base_amount = 0, $unique_code = 0, $final_amount = 0;

    public function mount()
    {
        $payment = Auth::user()->participant?->registration?->payment;
        if ($payment && $payment->payment_status === 'paid') {
            return redirect()->route('participant.dashboard'); 
        }

        $user = Auth::user();
        $this->is_international = ($user->nationality !== 'Indonesia');

        $participant = $user->participant;
        if ($participant) {
            // Load Data Step 1
            $this->type_of_institution = $participant->type_of_institution;
            $this->institution_address = $participant->institution_address;
            $this->province = $participant->province;
            $this->website_social_media = $participant->website_social_media;
            $this->institution_scale = $participant->institution_scale;
            $savedPosition = $participant->position_title;
            $standardPositions = [
                'Pimpinan/Pengasuh/Ketua Yayasan', 
                'Direktur', 
                'Kepala Lembaga/Sekolah', 
                'Dosen/Guru'
            ];

            if ($savedPosition && !in_array($savedPosition, $standardPositions)) {
                $this->position_title = 'Lain-lain';
                $this->other_position_title = $savedPosition;
            } else {
                $this->position_title = $savedPosition;
            }

            if ($participant->registration) {
                $reg = $participant->registration;

                // Load Partisipasi (STEP 1)
                $this->role_at_summit = $reg->role_at_summit;
                $this->showcase_category = $reg->showcase_category;
                $this->preferred_working_group = is_array($reg->preferred_working_group) ? $reg->preferred_working_group : json_decode($reg->preferred_working_group, true) ?? [];
                $this->willingness_to_cosign_declaration = $reg->willingness_to_cosign_declaration;

                // Load Logistik (STEP 2)
                $this->departure_city_country = $reg->departure_city_country;
                $this->estimated_arrival = $reg->estimated_arrival ? \Carbon\Carbon::parse($reg->estimated_arrival)->format('Y-m-d\TH:i') : null;
                $this->estimated_departure = $reg->estimated_departure ? \Carbon\Carbon::parse($reg->estimated_departure)->format('Y-m-d\TH:i') : null;
                $this->needs_accommodation_assist = $reg->needs_accommodation_assist;
                $this->requires_visa_letter = $reg->requires_visa_letter;
                $this->dietary_restrictions = $reg->dietary_restrictions;
                $this->accessibility_needs = $reg->accessibility_needs;
                $this->tour_guide_needed = $reg->tour_guide_needed;
                
                $this->selected_room_type = $reg->room_type_preference;
                // $this->selected_room_id = $reg->room_id;

                // Load Riwayat File & Kelas (STEP 3)
                $this->mandate_letter = $reg->mandate_letter_path; 
                $this->consent_data_use = $reg->consent_data_use;
                $this->selected_classes = $reg->additionalClasses->pluck('id')->toArray();
            }
        }
    }

    #[Computed]
    public function availableRooms()
    {
        if (!$this->room_type) return collect();
        
        return Room::where('type', $this->room_type)
                   ->where('is_available', true)
                   ->with('registrations.participant.user', 'registrations.payment') 
                   ->get()
                   ->filter(function($room) {
                       return $room->registrations->count() < $room->capacity;
                   });
    }

    #[Computed]
    public function additionalClassesList()
    {
        return AdditionalClass::where('is_active', true)->get();
    }

    public function nextStep()
    {
        $participant = Participant::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'position_title' => $this->position_title === 'Lain-lain' ? $this->other_position_title : $this->position_title,
                'type_of_institution' => $this->type_of_institution,
                'institution_address' => $this->institution_address,
                'province' => $this->province,
                'website_social_media' => $this->website_social_media,
                'institution_scale' => $this->institution_scale,
            ]
        );

        if ($this->currentStep == 1) {
            $this->validate([
                'position_title' => 'required',
                'other_position_title' => 'required_if:position_title,Lain-lain'
            ], [
                'other_position_title.required_if' => 'Please specify your position title.',
            ]);
            $finalPosition = $this->position_title === 'Lain-lain' 
                ? $this->other_position_title 
                : $this->position_title;

            Registration::updateOrCreate(
                ['participant_id' => $participant->id],
                
                [
                    'position_title' => $finalPosition,
                    'type_of_institution' => $this->type_of_institution,
                    'institution_address' => $this->institution_address,
                    'province' => $this->province,
                    'website_social_media' => $this->website_social_media,
                    'institution_scale' => $this->institution_scale,
                    'role_at_summit' => $this->role_at_summit,
                    'showcase_category' => $this->showcase_category,
                    'preferred_working_group' => $this->preferred_working_group, 
                    'willingness_to_cosign_declaration' => $this->willingness_to_cosign_declaration,
                ]
            );
        }
        elseif ($this->currentStep == 2) {
            $this->validate([
                'departure_city_country' => 'required|string|max:255',
                'selected_room_type'     => 'required|in:Single,Twin',
            ], [
                'selected_room_type.required' => 'Please select your accommodation type before proceeding.',
                'departure_city_country.required' => 'Departure city/country is required.',
            ]);
            Registration::updateOrCreate(
                ['participant_id' => $participant->id],
                [
                    'departure_city_country' => $this->departure_city_country,
                    'estimated_arrival' => $this->estimated_arrival, 
                    'estimated_departure' => $this->estimated_departure, 
                    'room_type_preference' => $this->selected_room_type,
                    // 'room_id' => $this->selected_room_id,
                    'needs_accommodation_assist' => $this->needs_accommodation_assist,
                    'requires_visa_letter' => $this->requires_visa_letter,
                    'dietary_restrictions' => $this->dietary_restrictions,
                    'accessibility_needs' => $this->accessibility_needs,
                    'tour_guide_needed' => $this->tour_guide_needed,
                ]
            );
        }
        elseif ($this->currentStep == 3) {
            $registration = Registration::where('participant_id', $participant->id)->first();

            if (!is_string($this->mandate_letter) && $this->mandate_letter != null) {
                $path = $this->mandate_letter->store('mandate_letters', 'public');
                $registration->update(['mandate_letter_path' => $path]);
            }

            $registration->update(['consent_data_use' => $this->consent_data_use]);
            
            $registration->additionalClasses()->sync($this->selected_classes);

            $this->generateInvoice($registration);
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    private function generateInvoice($registration)
    {
        $this->payment_currency = $this->is_international ? 'USD' : 'IDR';
        $total = 0;

        // $room = Room::find($this->selected_room_id);
        // if ($room) {
        //     $total += $this->is_international ? $room->price_usd : $room->price_idr;
        // }
        if ($this->selected_room_type) {
            $roomPattern = Room::where('type', $this->selected_room_type)->first();
            if ($roomPattern) {
                $total += $this->is_international ? $roomPattern->price_usd : $roomPattern->price_idr;
            }
        }

        $classes = AdditionalClass::whereIn('id', $this->selected_classes)->get();
        foreach ($classes as $class) {
            $total += $this->is_international ? $class->price_usd : $class->price_idr;
        }

        $this->base_amount = $total;
        
        $regId = $registration->id; 

        if ($this->payment_currency === 'USD') {
            $cents = ($regId % 99) + 1;
            $this->unique_code = round($cents / 100, 2); 
        } else {
            $this->unique_code = ($regId % 900) + 1;
        }
        
        $this->final_amount = $this->base_amount + $this->unique_code;
    }

    public function submitFinal()
    {
        $user = Auth::user();
        $registration = $user->participant->registration;
        
        $registration->update([
            'room_id' => null, 
            'room_type_preference' => $this->selected_room_type,
        ]);
        Payment::updateOrCreate(
            ['registration_id' => $registration->id],
            [
                'registration_category' => $this->is_international ? 'International' : 'Domestic',
                'currency' => $this->payment_currency,
                'base_amount' => $this->base_amount,
                'unique_code' => $this->unique_code,
                'final_amount' => $this->final_amount,
                'payment_status' => 'pending_verification', 
            ]
        );

        $formattedAmount = $this->payment_currency === 'IDR' 
            ? 'Rp ' . number_format($this->final_amount, 0, ',', '.') 
            : '$ ' . number_format($this->final_amount, 2, '.', ',');

        $formattedCode = $this->payment_currency === 'IDR'
            ? sprintf('%03d', $this->unique_code) 
            : '+$ ' . number_format($this->unique_code, 2, '.', '');

        $bankDetails = $this->payment_currency === 'IDR'
            ? "Bank: *BSI (Bank Syariah Indonesia)*\nNo. Rekening: *7353689268*\nAccount Name: *FORUM PESANTREN ALUMNI GONTOR*"
            : "Bank: *BSI (Bank Syariah Indonesia)*\nNo. Rekening: *7353689268*\nAccount Name: *FORUM PESANTREN ALUMNI GONTOR*";

        $dashboardUrl = route('participant.dashboard'); 
        
        $pesan = "Hello *{$user->name}*,\n\n"
            . "Thank you for completing your registration for GIHES 2026. Here is your payment invoice:\n\n"
            . "Category: *" . ($this->is_international ? 'International' : 'Domestic') . "*\n"
            . "Total Amount: *{$formattedAmount}* (Includes unique code: {$formattedCode})\n\n"
            . "Please transfer the exact amount to our official account:\n"
            . "{$bankDetails}\n\n"
            . "Once you have made the payment, please upload your transfer receipt to confirm your seat via your Participant Dashboard:\n"
            . "{$dashboardUrl}\n\n"
            . "Please note that your room and seat are *not secured* until the payment is verified.\n\n"
            . "Best regards,\n*GIHES 2026 Committee*";

        Http::withHeaders([
            'Authorization' => 'eYx7Pa6K2xiSE4s9aQxo'
        ])->post('https://api.fonnte.com/send', [
            'target' => $user->whatsapp,
            'message' => $pesan,
        ]);

        return redirect()->route('participant.dashboard');
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }
};
?>

<div class="max-w-4xl mx-auto pb-12">
    
    <div class="mb-8">
        <a href="{{ route('participant.dashboard') }}" class="text-sm text-gray-500 hover:text-[#C0A062] flex items-center gap-1 mb-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Dashboard
        </a>
        <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">Complete Registration</h1>
        <p class="text-gray-500 mt-1">Please provide the remaining details to secure your delegate pass.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            @for ($i = 1; $i <= $totalSteps; $i++)
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300
                        {{ $currentStep == $i ? 'bg-[#12241C] text-white ring-4 ring-[#C0A062]/30' : ($currentStep > $i ? 'bg-[#5A6446] text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($currentStep > $i)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @else
                            {{ $i }}
                        @endif
                    </div>
                    @if($i < $totalSteps)
                        <div class="w-12 sm:w-20 md:w-32 h-1 mx-2 rounded {{ $currentStep > $i ? 'bg-[#5A6446]' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endfor
        </div>

        <div class="p-6 md:p-10">
            <form wire:submit.prevent="nextStep">
                
                @if ($currentStep == 1)
                    <div class="animate-fade-in-up">
                        <div class="mb-8 border-b border-gray-200 pb-4">
                            <h2 class="text-2xl font-black text-[#1B1B1B] uppercase tracking-wide">Delegate & Institution Details</h2>
                            <p class="text-sm text-gray-500 mt-1">Information for the official directory and seating arrangement.</p>
                        </div>
                        
                        <div class="space-y-8"> 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Position / Title at Institution <span class="text-red-500">*</span></label>
                                    
                                    <select wire:model.live="position_title" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all bg-white text-gray-800">
                                        <option value="">-- Select Position --</option>
                                        <option value="Pimpinan/Pengasuh/Ketua Yayasan">Pimpinan / Pengasuh / Ketua Yayasan</option>
                                        <option value="Direktur">Direktur</option>
                                        <option value="Kepala Lembaga/Sekolah">Kepala Lembaga / Sekolah</option>
                                        <option value="Dosen/Guru">Dosen / Guru</option>
                                        <option value="Lain-lain">Lain-lain</option>
                                    </select>
                                    @error('position_title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                                    @if($position_title === 'Lain-lain')
                                        <div class="mt-3 animate-fade-in-up">
                                            <input type="text" wire:model="other_position_title" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] outline-none transition-all" placeholder="Please specify your position (e.g. Staff IT, Bendahara)...">
                                            @error('other_position_title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Type of Institution <span class="text-red-500">*</span></label>
                                    <select wire:model="type_of_institution" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all bg-white text-gray-800">
                                        <option value="">-- Select Type --</option>
                                        <option value="Pesantren">Pesantren / Islamic Boarding School</option>
                                        <option value="General/Public School">General / Public School</option>
                                        <option value="Madrasah">Madrasah</option>
                                        <option value="University">University</option>
                                        <option value="Government">Government Ministry / Agency</option>
                                        <option value="Islamic Organization">Islamic Organization</option>
                                        <option value="Philanthropic">Philanthropic Foundation / Waqf Body</option>
                                        <option value="Media">Media</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    @error('type_of_institution') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    {{ !$is_international ? 'Provinsi' : 'Province / State' }} <span class="text-red-500">*</span>
                                </label>

                                @if(!$is_international)
                                    <input list="indonesia-provinces" wire:model="province" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all bg-white" placeholder="Ketik atau pilih provinsi..." autocomplete="off">
                                    
                                    <datalist id="indonesia-provinces">
                                        <option value="Aceh"></option>
                                        <option value="Sumatera Utara"></option>
                                        <option value="Sumatera Barat"></option>
                                        <option value="Riau"></option>
                                        <option value="Jambi"></option>
                                        <option value="Sumatera Selatan"></option>
                                        <option value="Bengkulu"></option>
                                        <option value="Lampung"></option>
                                        <option value="Kepulauan Bangka Belitung"></option>
                                        <option value="Kepulauan Riau"></option>
                                        <option value="DKI Jakarta"></option>
                                        <option value="Jawa Barat"></option>
                                        <option value="Jawa Tengah"></option>
                                        <option value="DI Yogyakarta"></option>
                                        <option value="Jawa Timur"></option>
                                        <option value="Banten"></option>
                                        <option value="Bali"></option>
                                        <option value="Nusa Tenggara Barat"></option>
                                        <option value="Nusa Tenggara Timur"></option>
                                        <option value="Kalimantan Barat"></option>
                                        <option value="Kalimantan Tengah"></option>
                                        <option value="Kalimantan Selatan"></option>
                                        <option value="Kalimantan Timur"></option>
                                        <option value="Kalimantan Utara"></option>
                                        <option value="Sulawesi Utara"></option>
                                        <option value="Sulawesi Tengah"></option>
                                        <option value="Sulawesi Selatan"></option>
                                        <option value="Sulawesi Tenggara"></option>
                                        <option value="Gorontalo"></option>
                                        <option value="Sulawesi Barat"></option>
                                        <option value="Maluku"></option>
                                        <option value="Maluku Utara"></option>
                                        <option value="Papua"></option>
                                        <option value="Papua Barat"></option>
                                        <option value="Papua Selatan"></option>
                                        <option value="Papua Tengah"></option>
                                        <option value="Papua Pegunungan"></option>
                                        <option value="Papua Barat Daya"></option>
                                    </datalist>
                                @else
                                    <input type="text" wire:model="province" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="e.g. Selangor, Texas, etc.">
                                @endif

                                @error('province') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Full Institution Address <span class="text-red-500">*</span></label>
                                <textarea wire:model="institution_address" rows="3" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="Complete street address..."></textarea>
                                @error('institution_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-200">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Website / Social Media <span class="text-gray-400 font-normal text-xs ml-1">(Optional)</span></label>
                                    <input type="text" wire:model="website_social_media" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="https://...">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Institution Scale <span class="text-gray-400 font-normal text-xs ml-1">(Optional)</span></label>
                                    <input type="text" wire:model="institution_scale" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="e.g. 5000 students, 3 branches">
                                </div>
                            </div>

                            <div class="pt-8 mt-4 border-t border-gray-200">
                                <h3 class="text-lg font-bold text-[#1B1B1B] mb-6">Participation Category & Preferences</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Role at Summit <span class="text-red-500">*</span></label>
                                        <select wire:model.live="role_at_summit" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] bg-white">
                                            <option value="">-- Select Role --</option>
                                            <option value="Participant">Participant</option>
                                            <!-- <option value="Speaker">Speaker</option>
                                            <option value="Exhibitor">Exhibitor (Institutional Showcase)</option>
                                            <option value="Media">Media</option>
                                            <option value="Partner/Sponsor">Partner / Sponsor</option> -->
                                        </select>
                                    </div>

                                    <!-- @if($role_at_summit === 'Exhibitor')
                                    <div class="animate-fade-in-up">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Institutional Showcase Category <span class="text-red-500">*</span></label>
                                        <select wire:model="showcase_category" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] bg-white">
                                            <option value="">-- Select Category --</option>
                                            <option value="Curriculum">Curriculum</option>
                                            <option value="Leadership">Leadership</option>
                                            <option value="Language">Language</option>
                                            <option value="Organization">Organization</option>
                                            <option value="Entrepreneurship">Entrepreneurship</option>
                                        </select>
                                    </div>
                                    @endif -->
                                </div>

                                <div class="grid grid-cols-1 gap-8 mt-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-3">Preferred Working Group (Check all that apply)</label>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @php
                                                $groups = ['Kurikulum Holistik', 'Kepemimpinan', 'Pendidikan Bahasa Arab dan Adab', 'Foundation & Institutional Development', 'Pesantren dan Mental Health', 'Digital/AI dalam Pendidikan'];
                                            @endphp
                                            @foreach($groups as $group)
                                                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded cursor-pointer hover:bg-gray-50">
                                                    <input type="checkbox" wire:model="preferred_working_group" value="{{ $group }}" class="w-4 h-4 text-[#C0A062] focus:ring-[#C0A062]">
                                                    <span class="text-sm text-gray-700">{{ $group }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-3">Willingness to co-sign the GIHES Declaration <span class="text-red-500">*</span></label>
                                        <div class="space-y-2">
                                            <label class="flex items-center gap-3"><input type="radio" wire:model="willingness_to_cosign_declaration" value="Yes, institution is willing" class="text-[#C0A062]"><span class="text-sm">Yes, institution is willing</span></label>
                                            <label class="flex items-center gap-3"><input type="radio" wire:model="willingness_to_cosign_declaration" value="Need internal approval first" class="text-[#C0A062]"><span class="text-sm">Need internal approval first</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif

                @if ($currentStep == 2)
                    <div class="animate-fade-in-up">
                        <div class="mb-8 border-b border-gray-200 pb-4">
                            <h2 class="text-2xl font-black text-[#1B1B1B] uppercase tracking-wide">Logistics & Accommodation</h2>
                            <p class="text-sm text-gray-500 mt-1">Plan your arrival and select your preferred accommodation.</p>
                        </div>
                        
                        <div class="space-y-8">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Accommodation Assistance Needed?</label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2"><input type="radio" wire:model="needs_accommodation_assist" value="1" class="text-[#C0A062]"> Yes, please assist</label>
                                            <label class="flex items-center gap-2"><input type="radio" wire:model="needs_accommodation_assist" value="0" class="text-[#C0A062]"> No, self-arranged</label>
                                        </div>
                                    </div>
                                    
                                    @if($is_international)
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Require Official Invitation Letter for Visa?</label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2"><input type="radio" wire:model="requires_visa_letter" value="1" class="text-[#C0A062]"> Yes</label>
                                            <label class="flex items-center gap-2"><input type="radio" wire:model="requires_visa_letter" value="0" class="text-[#C0A062]"> No</label>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Dietary Restrictions or Allergies <span class="text-gray-400 font-normal text-xs">(Halal is standard)</span></label>
                                        <input type="text" wire:model="dietary_restrictions" class="w-full border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062]" placeholder="e.g. Seafood allergy, Vegetarian">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Accessibility Needs</label>
                                        <input type="text" wire:model="accessibility_needs" class="w-full border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062]" placeholder="e.g. Wheelchair access">
                                    </div>
                                </div>

                                
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Departure City / Country <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="departure_city_country" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:ring-2 focus:ring-[#C0A062] outline-none" placeholder="e.g. Kuala Lumpur, Malaysia">
                                    @error('departure_city_country') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                
                            </div>

                            @if($is_international)
                            <div class="bg-blue-50 p-4 border border-blue-200 rounded-md">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model="tour_guide_needed" class="mt-1 w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    <div>
                                        <span class="block font-bold text-blue-900">Request Local Tour Guide (For International Delegates)</span>
                                        <span class="block text-sm text-blue-700 mt-1">Check this box if you need assistance with transportation and cultural tours in Jakarta.</span>
                                    </div>
                                </label>
                            </div>
                            @endif

                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-bold text-[#1B1B1B] mb-4">Accommodation Setup</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" wire:model.live="selected_room_type" value="Single" class="peer sr-only">
                                        <div class="border-2 rounded-lg p-5 transition-all duration-200 border-gray-200 hover:bg-gray-50 peer-checked:border-[#C0A062] peer-checked:bg-[#C0A062]/10">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <span class="block font-black text-lg text-gray-900">Single Bed</span>
                                                    <span class="block text-sm text-gray-500 mt-1">1 King Bed / Private</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="block font-bold text-[#5A6446]">Rp 4.500.000</span>
                                                    <span class="block text-xs text-gray-400">/ USD 300</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-[#C0A062] transition-opacity">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" wire:model.live="selected_room_type" value="Twin" class="peer sr-only">
                                        <div class="border-2 rounded-lg p-5 transition-all duration-200 border-gray-200 hover:bg-gray-50 peer-checked:border-[#C0A062] peer-checked:bg-[#C0A062]/10">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <span class="block font-black text-lg text-gray-900">Twin Beds</span>
                                                    <span class="block text-sm text-gray-500 mt-1">2 Beds / Shared</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="block font-bold text-[#5A6446]">Rp 3.000.000</span>
                                                    <span class="block text-xs text-gray-400">/ USD 200</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-[#C0A062] transition-opacity">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </label>
                                </div>
                                @error('selected_room_type') <span class="text-red-500 text-xs block mb-4">{{ $message }}</span> @enderror

                                @if($selected_room_type)
                                    <div class="animate-fade-in-up bg-blue-50 p-4 rounded-md border border-blue-200 flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-sm text-blue-800 leading-relaxed">
                                            <strong>Room Assignment:</strong> Your room number will be assigned by the organizing committee. The exact room details will be updated on your dashboard prior to the event.
                                        </p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endif

                @if ($currentStep == 3)
                    <div class="animate-fade-in-up">
                        <div class="mb-8 border-b border-gray-200 pb-4">
                            <h2 class="text-2xl font-black text-[#1B1B1B] uppercase tracking-wide">Classes & Administration</h2>
                            <p class="text-sm text-gray-500 mt-1">Select additional classes and upload official documents.</p>
                        </div>
                        
                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Additional Classes / Tours</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($this->additionalClassesList as $class)
                                    <label class="flex items-start gap-4 p-4 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:border-[#C0A062] hover:shadow-md bg-white group">
                                        
                                        <input type="checkbox" wire:model="selected_classes" value="{{ $class->id }}" class="mt-1 w-5 h-5 text-[#C0A062] focus:ring-[#C0A062] rounded-sm cursor-pointer">
                                        
                                        <div class="flex-1 flex justify-between items-start gap-2">
                                            <div>
                                                <span class="block font-bold text-gray-900 group-hover:text-[#5A6446] transition-colors">{{ $class->name }}</span>
                                                <span class="block text-xs text-gray-500 mt-1 leading-relaxed">{{ $class->description }}</span>
                                                <p class="text-sm text-gray-500">
                                                    <i class="heroicon-o-calendar"></i> {{ $class->day }} | 
                                                    <i class="heroicon-o-clock"></i> {{ \Carbon\Carbon::parse($class->time)->format('H:i') }} | 
                                                    <i class="heroicon-o-map-pin"></i> {{ $class->location }}
                                                </p>
                                            </div>
                                            
                                            <div class="shrink-0 text-right">
                                                @if($is_international)
                                                    @if($class->price_usd > 0)
                                                        <span class="inline-block bg-blue-50 text-blue-700 text-xs font-black px-2 py-1 rounded border border-blue-200 tracking-wide">
                                                            USD {{ number_format($class->price_usd, 0) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-green-50 text-green-700 text-xs font-black px-2 py-1 rounded border border-green-200 uppercase tracking-wider">
                                                            Free
                                                        </span>
                                                    @endif
                                                @else
                                                    @if($class->price_idr > 0)
                                                        <span class="inline-block bg-blue-50 text-blue-700 text-xs font-black px-2 py-1 rounded border border-blue-200 tracking-wide whitespace-nowrap">
                                                            Rp {{ number_format($class->price_idr, 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-green-50 text-green-700 text-xs font-black px-2 py-1 rounded border border-green-200 uppercase tracking-wider">
                                                            Gratis
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6 space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Upload Official Mandate / Assignment Letter <span class="text-red-500">*</span></label>
                                
                                @if(is_string($mandate_letter) && $mandate_letter)
                                    <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded flex items-center justify-between">
                                        <div class="flex items-center gap-2 text-sm text-green-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Document uploaded: <strong>{{ basename($mandate_letter) }}</strong></span>
                                        </div>
                                        <button type="button" wire:click="$set('mandate_letter', null)" class="text-xs font-bold text-red-600 hover:underline">Change File</button>
                                    </div>
                                @endif

                                @if(!is_string($mandate_letter))
                                    <input type="file" wire:model="mandate_letter" class="w-full border border-gray-300 rounded-sm p-2 text-sm bg-gray-50" accept=".pdf,.jpg,.jpeg,.png">
                                    <span class="text-xs text-gray-400 mt-1 block">PDF or Image format max 2MB.</span>
                                    @error('mandate_letter') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                @endif
                            </div>

                            <label class="flex items-start gap-3 cursor-pointer bg-gray-50 p-4 rounded border border-gray-200">
                                <input type="checkbox" wire:model="consent_data_use" class="mt-0.5 w-5 h-5 text-[#C0A062] focus:ring-[#C0A062]">
                                <span class="text-sm text-gray-700 font-medium leading-snug">I consent to the use of my data and documentation (photo/video) for official GIHES 2026 publications. <span class="text-red-500">*</span></span>
                            </label>
                        </div>
                    </div>
                @endif

                @if ($currentStep == 4)
                    <div class="animate-fade-in-up" x-data="{ copiedRek: false, copiedAmount: false }">
                        <div class="mb-8 border-b border-gray-200 pb-4 text-center">
                            <h2 class="text-2xl font-black text-[#1B1B1B] uppercase tracking-wide">Invoice & Payment</h2>
                            <p class="text-sm text-gray-500 mt-1">Please complete your payment to secure your registration.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                            
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Invoice Summary</p>
                                    <p class="text-sm font-bold text-[#1B1B1B] mt-1">{{ auth()->user()->name }}</p>
                                </div>

                                <div class="p-6 flex-1">
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-xs text-gray-500 uppercase border-b border-gray-200">
                                            <tr>
                                                <th class="pb-3">Description</th>
                                                <th class="pb-3 text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            
                                            <tr>
                                                <td class="py-4">
                                                    <p class="font-bold text-gray-900">Accommodation: {{ $room_type }}</p>
                                                    <p class="text-xs text-gray-500 mt-0.5">Duration of the summit</p>
                                                </td>
                                                <td class="py-4 text-right font-medium text-gray-900">
                                                    @php
                                                        $roomPattern = \App\Models\Room::where('type', $selected_room_type)->first();
                                                        $roomPrice = $is_international ? ($roomPattern?->price_usd ?? 0) : ($roomPattern?->price_idr ?? 0);
                                                    @endphp
                                                    {{ $payment_currency }} {{ $is_international ? number_format($roomPrice, 2) : number_format($roomPrice, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            
                                            @foreach($this->additionalClassesList->whereIn('id', $selected_classes) as $class)
                                                @php
                                                    $classPrice = $is_international ? $class->price_usd : $class->price_idr;
                                                @endphp
                                                @if($classPrice > 0)
                                                <tr>
                                                    <td class="py-4">
                                                        <p class="font-bold text-gray-900">{{ $class->name }}</p>
                                                    </td>
                                                    <td class="py-4 text-right font-medium text-gray-900">
                                                        {{ $payment_currency }} {{ $is_international ? number_format($classPrice, 2) : number_format($classPrice, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="bg-[#12241C] text-white p-6">
                                    <div class="flex justify-between items-center mb-2 text-sm text-gray-300">
                                        <span>Base Amount</span>
                                        <span>{{ $payment_currency }} {{ $is_international ? number_format($base_amount, 2) : number_format($base_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-4 text-sm text-[#C0A062]">
                                        <span>Unique Code</span>
                                        <span>+ {{ $is_international ? ($unique_code / 100) : $unique_code }}</span>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-gray-600 pt-4">
                                        <span class="font-bold">Total Transfer</span>
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl font-black text-[#C0A062]" id="totalAmountText">
                                                {{ $payment_currency }} {{ $is_international ? number_format($final_amount, 2) : number_format($final_amount, 0, ',', '.') }}
                                            </span>
                                            <button type="button" @click="navigator.clipboard.writeText('{{ $is_international ? $final_amount : (int)$final_amount }}'); copiedAmount = true; setTimeout(() => copiedAmount = false, 2000)" class="text-gray-400 hover:text-white transition-colors" title="Copy Amount">
                                                <svg x-show="!copiedAmount" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                <svg x-show="copiedAmount" x-cloak class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Payment Instructions</p>
                                </div>
                                
                                <div class="p-6 space-y-6">
                                    <div class="border border-[#C0A062] rounded-lg p-5 bg-yellow-50/30">
                                        <p class="text-xs font-bold text-[#5A6446] uppercase mb-3">Bank Transfer</p>
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded flex items-center justify-center border border-gray-200 shrink-0 p-1">
                                                <img src="{{ asset('images/bsi-logo.png') }}" alt="Logo BSI" class="w-full h-full object-contain">
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-500">Account Name</p>
                                                <p class="font-bold text-gray-900 text-sm">Forum Pesantren Alumni Gontor</p>
                                                
                                                <p class="text-xs text-gray-500 mt-2">Account Number</p>
                                                <div class="flex items-center gap-2">
                                                    <p class="font-black text-lg text-[#12241C] tracking-widest">7353689268</p>
                                                    <button type="button" @click="navigator.clipboard.writeText('7353689268'); copiedRek = true; setTimeout(() => copiedRek = false, 2000)" class="text-gray-400 hover:text-[#C0A062] transition-colors">
                                                        <svg x-show="!copiedRek" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                        <svg x-show="copiedRek" x-cloak class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div class="relative flex py-2 items-center">
                                            <div class="flex-grow border-t border-gray-200"></div>
                                            <span class="shrink-0 mx-4 text-gray-400 text-xs uppercase font-bold">OR PAY VIA QRIS</span>
                                            <div class="flex-grow border-t border-gray-200"></div>
                                        </div>
                                        
                                        <div class="mt-4 inline-block p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                            <img src="{{ asset('images/bsi-logo.png') }}" alt="QRIS GIHES" class="w-32 h-32 object-cover mx-auto opacity-80">
                                            <p class="text-xs font-bold mt-2 text-gray-800">FORUM PESANTREN ALUMNI GONTOR</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif

                <div class="mt-10 flex justify-between items-center border-t border-gray-200 pt-6">
                    @if ($currentStep > 1)
                        <button type="button" wire:click="previousStep" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-sm font-bold hover:bg-gray-50 transition">
                            Previous Step
                        </button>
                    @else
                        <div></div> 
                    @endif

                    @if ($currentStep < $totalSteps)
                        <button type="submit" class="px-8 py-3 bg-[#12241C] text-white rounded-sm font-bold hover:bg-[#1a3328] transition shadow-md flex items-center gap-2">
                            Save & Continue
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    @else
                        <button type="button" wire:click="submitFinal" class="px-8 py-3 bg-[#C0A062] text-[#12241C] rounded-sm font-black uppercase tracking-widest hover:bg-[#a3854d] transition shadow-lg">
                            Confirm & Pay
                        </button>
                    @endif
                </div>

            </form>
        </div>
    </div>
    @script
    <script>
        $wire.on('open-wa-and-redirect', (data) => {
            window.open(data.waLink, '_blank');
            window.location.href = data.panelUrl;
        });
    </script>
    @endscript
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>