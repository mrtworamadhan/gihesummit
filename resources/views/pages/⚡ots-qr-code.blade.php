<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Country;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\AdditionalClass;
use Livewire\Attributes\Layout;


new #[Layout('layouts::guest')] class extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 4;
    public $is_international = false;

    // DATA AKUN BARU (Dari Source 3)
    public $name, $email, $whatsapp, $institution_name, $nationality, $password, $password_confirmation;
    public $phone_code = '62';
    public $gender;

    // STEP 1 WIZARD (Dari Source 2)
    public $type_of_institution, $institution_address, $province, $website_social_media, $institution_scale, $position_title;
    public $role_at_summit, $showcase_category, $willingness_to_cosign_declaration;
    public $preferred_working_group = [];
    public $other_position_title;

    // STEP 2 WIZARD
    public $departure_city_country, $estimated_arrival, $estimated_departure;
    public $tour_guide_needed = false; 
    public $needs_accommodation_assist = 0, $requires_visa_letter = 0;
    public $dietary_restrictions, $accessibility_needs;

    // STEP 3 WIZARD
    public $mandate_letter; 
    public $consent_data_use = true;
    public $selected_class = null;

    // HASIL FINAL
    public $generatedBarcode = '';
    public $isSuccess = false;

    // MENGAMBIL DATA NEGARA
    public function getCountriesProperty()
    {
        return Country::orderBy('nicename', 'asc')->get();
    }

    public function updatedPhoneCode($value)
    {
        $country = collect($this->countries)->firstWhere('phonecode', $value);
        if ($country) {
            $this->nationality = $country->nicename; 
        }
    }

    public function updatedNationality($value)
    {
        $country = collect($this->countries)->firstWhere('nicename', $value);
        if ($country) {
            $this->phone_code = $country->phonecode; 
        }
    }

    #[Computed]
    public function additionalClassesList()
    {
        return AdditionalClass::where('is_active', true)->get();
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            // Validasi Gabungan (Register Account + Step 1 Wizard)
            $this->validate([
                'name' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female,Laki-laki,Perempuan',
                'email' => 'required|email|unique:users,email',
                'whatsapp' => 'required|string|min:9|max:15',
                'institution_name' => 'required|string|max:255',
                'nationality' => 'required|string',
                'password' => 'required|min:8|confirmed',
                'position_title' => 'required',
                'other_position_title' => 'required_if:position_title,Lain-lain',
                'type_of_institution' => 'required',
                'province' => 'required',
                'institution_address' => 'required',
                'role_at_summit' => 'required',
                'willingness_to_cosign_declaration' => 'required'
            ]);

            // Cek duplikasi nomor WA dengan format khusus
            $cleanWhatsapp = ltrim($this->whatsapp, '0');
            $fullWhatsappNumber = $this->phone_code . $cleanWhatsapp;
            if (User::where('whatsapp', $fullWhatsappNumber)->exists()) {
                $this->addError('whatsapp', 'This WhatsApp number is already registered.');
                return;
            }

            // Set deteksi internasional untuk Step 2
            $this->is_international = ($this->nationality !== 'Indonesia');
        }
        elseif ($this->currentStep == 2) {
            $this->validate([
                'departure_city_country' => 'required|string|max:255',
            ]);
        }
        elseif ($this->currentStep == 3) {
            if ($this->mandate_letter) {
                $this->validate(['mandate_letter' => 'image|max:2048|mimes:jpg,jpeg,png,pdf']);
            }
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submitFinal()
    {
        $cleanWhatsapp = ltrim($this->whatsapp, '0');
        $fullWhatsappNumber = $this->phone_code . $cleanWhatsapp;

        // 1. Buat User Baru secara utuh
        $user = User::create([
            'name' => $this->name,
            'gender' => $this->gender,
            'email' => $this->email,
            'whatsapp' => $fullWhatsappNumber,
            'institution_name' => $this->institution_name, 
            'nationality' => $this->nationality,
            'password' => Hash::make($this->password),
            'role' => 'participant',
        ]);

        $finalPosition = $this->position_title === 'Lain-lain' ? $this->other_position_title : $this->position_title;

        // 2. Buat Participant & UUID
        $participant = Participant::create([
            'user_id' => $user->id,
            'phone' => $fullWhatsappNumber,
            'uuid_barcode' => Str::uuid()->toString(),
            'position_title' => $finalPosition,
            'type_of_institution' => $this->type_of_institution,
            'institution_address' => $this->institution_address,
            'province' => $this->province,
            'website_social_media' => $this->website_social_media,
            'institution_scale' => $this->institution_scale,
        ]);

        // 3. Upload Mandate Letter jika ada
        $mandatePath = null;
        if ($this->mandate_letter) {
            $mandatePath = $this->mandate_letter->store('mandate_letters', 'public');
        }

        // 4. Registrasi Tanpa Kamar
        $registration = Registration::create([
            'participant_id' => $participant->id,
            'gender' => $this->gender,
            'role_at_summit' => $this->role_at_summit,
            'showcase_category' => $this->showcase_category,
            'preferred_working_group' => is_array($this->preferred_working_group) ? json_encode($this->preferred_working_group) : $this->preferred_working_group,
            'willingness_to_cosign_declaration' => $this->willingness_to_cosign_declaration,
            'departure_city_country' => $this->departure_city_country,
            'estimated_arrival' => $this->estimated_arrival,
            'estimated_departure' => $this->estimated_departure,
            'needs_accommodation_assist' => $this->needs_accommodation_assist,
            'requires_visa_letter' => $this->requires_visa_letter,
            'dietary_restrictions' => $this->dietary_restrictions,
            'accessibility_needs' => $this->accessibility_needs,
            'tour_guide_needed' => $this->tour_guide_needed,
            'room_type_preference' => 'No Accommodation', 
            'room_id' => null, 
            'mandate_letter_path' => $mandatePath,
            'consent_data_use' => $this->consent_data_use,
        ]);

        // 5. Sync Kelas Tambahan
        if ($this->selected_class) {
            $registration->additionalClasses()->sync([$this->selected_class]);
        }

        // 6. Payment (Kunci 2 Juta, Langsung Lunas)
        Payment::create([
            'registration_id' => $registration->id,
            'registration_category' => $this->is_international ? 'International' : 'Domestic',
            'currency' => 'IDR',
            'base_amount' => 2000000,
            'unique_code' => 0,
            'final_amount' => 2000000,
            'payment_status' => 'pending_verification', // <-- Diubah agar nyangkut di sistem admin dulu
            'payment_method' => 'On The Spot'
        ]);

        // 7. Notifikasi WA ke Peserta (Hapus Barcode-nya)
        $pesan = "Halo *{$user->name}*,\n\n"
            . "Terima kasih telah melakukan Registrasi On The Spot (OTS) untuk acara GIHES 2026.\n\n"
            . "Detail Tiket:\n"
            . "- Total Biaya: *Rp 2.000.000* (Tanpa Akomodasi)\n\n"
            . "Silakan segera menuju meja kasir panitia untuk menyelesaikan pembayaran secara tunai/transfer.\n\n"
            . "KODE BARCODE akses ruangan Anda akan otomatis dikirimkan ke WhatsApp ini HANYA SETELAH panitia memverifikasi pembayaran Anda di meja kasir.\n\n"
            . "Salam,\n*Panitia GIHES 2026*";

        Http::withHeaders([
            'Authorization' => 'eYx7Pa6K2xiSE4s9aQxo'
        ])->post('https://api.fonnte.com/send', [
            'target' => $fullWhatsappNumber, 
            'message' => $pesan,
        ]);

        $this->isSuccess = true;
    }
};
?>

<div class="max-w-4xl mx-auto pb-12 pt-8 font-sans">
    
    <div class="mb-8 text-center px-4">
        <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">REGISTRASI ON THE SPOT</h1>
        <p class="text-gray-500 mt-1">GIHES 2026 - Tiket Tanpa Akomodasi</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mx-4">
        
        @if($isSuccess)
            <div class="p-10 text-center animate-[fadeIn_0.5s_ease-out]">
                <div class="w-20 h-20 bg-yellow-500/20 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <!-- Icon Jam Pasir / Pending -->
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Pembayaran!</h3>
                <p class="text-gray-500 text-sm mb-6">Tunjukkan layar ini ke meja kasir untuk verifikasi pembayaran <strong>Rp 2.000.000</strong>.</p>
                
                <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200 mb-8">
                    <p class="text-sm font-bold text-yellow-800 uppercase tracking-wider mb-2">Barcode Belum Aktif</p>
                    <p class="text-sm text-yellow-700 leading-relaxed">
                        Barcode akses akan otomatis dikirimkan ke WhatsApp Anda setelah panitia menekan tombol "Approve" di sistem kasir.
                    </p>
                </div>
                
                <button onclick="window.location.reload()" class="w-full md:w-auto bg-[#12241C] hover:bg-[#1a3328] text-white font-bold py-3 px-8 rounded-lg transition shadow-md">
                    Daftarkan Peserta Lain
                </button>
            </div>
        @else
            <!-- WIZARD PROGRESS BAR -->
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                @for ($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300
                            {{ $currentStep == $i ? 'bg-[#12241C] text-white ring-4 ring-[#C0A062]/30' : ($currentStep > $i ? 'bg-[#5A6446] text-white' : 'bg-gray-200 text-gray-400') }}">
                            {{ $i }}
                        </div>
                        @if($i < $totalSteps)
                            <div class="w-10 sm:w-16 md:w-24 h-1 mx-2 rounded {{ $currentStep > $i ? 'bg-[#5A6446]' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                @endfor
            </div>

            <div class="p-6 md:p-10">
                <form wire:submit.prevent="nextStep">
                    
                    @if ($currentStep == 1)
                        <div class="animate-fade-in-up">
                            <div class="mb-8 border-b border-gray-200 pb-4">
                                <h2 class="text-xl font-black text-[#1B1B1B] uppercase tracking-wide">Data Akun & Instansi</h2>
                            </div>
                            
                            <div class="space-y-6">
                                
                                <!-- DATA AKUN -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name (Sesuai ID) <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="name" class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg p-3 focus:ring-2 focus:ring-[#C0A062] outline-none shadow-sm">
                                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Active WhatsApp Number <span class="text-red-500">*</span></label>
                                        <div class="flex shadow-sm rounded-lg">
                                            <select wire:model.live="phone_code" class="w-28 border border-gray-300 border-r-0 rounded-l-lg px-2 py-3 bg-gray-50 focus:ring-2 focus:ring-[#C0A062] outline-none">
                                                @foreach($this->countries as $country)
                                                    <option value="{{ $country->phonecode }}">+{{ $country->phonecode }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" wire:model="whatsapp" class="flex-grow border border-gray-300 rounded-r-lg px-4 py-3 focus:ring-2 focus:ring-[#C0A062] outline-none" placeholder="8123456789">
                                        </div>
                                        <span class="text-gray-400 text-[10px] mt-1 block">Omit leading zero (e.g., 812...)</span>
                                        @error('whatsapp') <span class="text-red-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Gender / Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <div class="flex gap-6">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" wire:model="gender" value="Laki-laki" class="w-4 h-4 text-[#C0A062] focus:ring-[#C0A062]">
                                            <span class="ml-2 text-sm text-gray-700">Male (Laki-laki)</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" wire:model="gender" value="Perempuan" class="w-4 h-4 text-[#C0A062] focus:ring-[#C0A062]">
                                            <span class="ml-2 text-sm text-gray-700">Female (Perempuan)</span>
                                        </label>
                                    </div>
                                    @error('gender') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Institution Name <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="institution_name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#C0A062] outline-none shadow-sm">
                                        @error('institution_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Nationality <span class="text-red-500">*</span></label>
                                        <select wire:model.live="nationality" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#C0A062] outline-none shadow-sm bg-white">
                                            <option value="">-- Select Country --</option>
                                            @foreach($this->countries as $country)
                                                <option value="{{ $country->nicename }}">{{ $country->nicename }}</option>
                                            @endforeach
                                        </select>
                                        @error('nationality') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h3 class="text-sm font-black tracking-widest text-[#5A6446] uppercase mb-4">Login Credentials (Untuk Dashboard)</h3>
                                    
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                            <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#C0A062] outline-none shadow-sm">
                                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ show: false }">
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                                                <div class="relative shadow-sm rounded-lg">
                                                    <input :type="show ? 'text' : 'password'" wire:model="password" class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-3 focus:ring-2 focus:ring-[#C0A062] outline-none" placeholder="Min. 8 karakter">
                                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-[#C0A062]">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    </button>
                                                </div>
                                                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                                                <div class="relative shadow-sm rounded-lg">
                                                    <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-3 focus:ring-2 focus:ring-[#C0A062] outline-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-6 border-gray-200">
                                
                                <!-- DATA INSTANSI -->
                                <h3 class="text-sm font-black tracking-widest text-[#5A6446] uppercase mb-4">Detail Instansi & Kehadiran</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Posisi / Jabatan <span class="text-red-500">*</span></label>
                                        <select wire:model.live="position_title" class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg p-3 focus:ring-2 focus:ring-[#C0A062] outline-none shadow-sm">
                                            <option value="">-- Select Position --</option>
                                            <option value="Pimpinan/Pengasuh/Ketua Yayasan">Pimpinan / Pengasuh / Ketua Yayasan</option>
                                            <option value="Direktur">Direktur</option>
                                            <option value="Kepala Lembaga/Sekolah">Kepala Lembaga / Sekolah</option>
                                            <option value="Dosen/Guru">Dosen / Guru</option>
                                            <option value="Lain-lain">Lain-lain</option>
                                        </select>
                                        @error('position_title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        
                                        @if($position_title === 'Lain-lain')
                                            <input type="text" wire:model="other_position_title" class="w-full border border-gray-300 bg-white rounded-lg p-3 mt-3 focus:ring-2 focus:ring-[#C0A062] shadow-sm" placeholder="Sebutkan jabatan...">
                                        @endif
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Instansi <span class="text-red-500">*</span></label>
                                        <select wire:model="type_of_institution" class="w-full border border-gray-300 bg-white rounded-lg p-3 focus:ring-2 focus:ring-[#C0A062] outline-none shadow-sm">
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
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap Instansi <span class="text-red-500">*</span></label>
                                        <textarea wire:model="institution_address" rows="2" class="w-full border border-gray-300 bg-white rounded-lg p-3 focus:ring-2 focus:ring-[#C0A062] outline-none shadow-sm"></textarea>
                                        @error('institution_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Peserta <span class="text-red-500">*</span></label>
                                        <select wire:model="role_at_summit" class="w-full border border-gray-300 bg-white rounded-lg p-3 focus:ring-2 focus:ring-[#C0A062] outline-none shadow-sm">
                                            <option value="">-- Pilih --</option>
                                            <option value="Participant">Participant</option>
                                        </select>
                                        @error('role_at_summit') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Website / Social Media <span class="text-gray-400 font-normal text-xs ml-1">(Opsional)</span></label>
                                        <input type="text" wire:model="website_social_media" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-[#C0A062] shadow-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Skala Instansi <span class="text-gray-400 font-normal text-xs ml-1">(Opsional)</span></label>
                                        <input type="text" wire:model="institution_scale" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-[#C0A062] shadow-sm" placeholder="Contoh: 500 Santri">
                                    </div>
                                </div>
                                
                                <div class="pt-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Preferred Working Group (Opsional)</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                                        @php
                                            $groups = ['Kurikulum Holistik', 'Kepemimpinan', 'Pendidikan Bahasa Arab', 'Digital/AI dalam Pendidikan'];
                                        @endphp
                                        @foreach($groups as $group)
                                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="checkbox" wire:model="preferred_working_group" value="{{ $group }}" class="w-4 h-4 text-[#C0A062] focus:ring-[#C0A062]">
                                                <span class="text-sm text-gray-700">{{ $group }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <label class="block text-sm font-bold text-gray-700 mb-3">Kesediaan menandatangani Deklarasi GIHES <span class="text-red-500">*</span></label>
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                            <input type="radio" wire:model="willingness_to_cosign_declaration" value="Yes, institution is willing" class="w-4 h-4 text-[#C0A062] focus:ring-[#C0A062]">
                                            <span class="text-sm font-medium text-gray-700">Ya, instansi bersedia</span>
                                        </label>
                                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                            <input type="radio" wire:model="willingness_to_cosign_declaration" value="Need internal approval first" class="w-4 h-4 text-[#C0A062] focus:ring-[#C0A062]">
                                            <span class="text-sm font-medium text-gray-700">Perlu persetujuan internal terlebih dahulu</span>
                                        </label>
                                    </div>
                                    @error('willingness_to_cosign_declaration') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($currentStep == 2)
                        <div class="animate-fade-in-up">
                            <div class="mb-8 border-b border-gray-200 pb-4">
                                <h2 class="text-xl font-black text-[#1B1B1B] uppercase tracking-wide">Logistik & Informasi Tambahan</h2>
                            </div>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kota Keberangkatan <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="departure_city_country" class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg p-3 focus:ring-2 focus:ring-[#C0A062] outline-none transition shadow-sm">
                                    @error('departure_city_country') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                @if($is_international)
                                    <div class="bg-blue-50 p-4 border border-blue-200 rounded-md mb-6">
                                        <label class="block text-sm font-bold text-blue-900 mb-2">Require Official Invitation Letter for Visa?</label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2"><input type="radio" wire:model="requires_visa_letter" value="1" class="text-[#C0A062]"> Yes</label>
                                            <label class="flex items-center gap-2"><input type="radio" wire:model="requires_visa_letter" value="0" class="text-[#C0A062]"> No</label>
                                        </div>
                                    </div>
                                    <div class="bg-blue-50 p-4 border border-blue-200 rounded-md">
                                        <label class="flex items-start gap-3 cursor-pointer">
                                            <input type="checkbox" wire:model="tour_guide_needed" class="mt-1 w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                            <div>
                                                <span class="block font-bold text-blue-900">Request Local Tour Guide</span>
                                                <span class="block text-sm text-blue-700 mt-1">Check this box if you need assistance with transportation.</span>
                                            </div>
                                        </label>
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Alergi Makanan / Pantangan</label>
                                        <input type="text" wire:model="dietary_restrictions" class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg p-3 focus:ring-2 focus:ring-[#C0A062] shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Kebutuhan Aksesibilitas Khusus</label>
                                        <input type="text" wire:model="accessibility_needs" class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg p-3 focus:ring-2 focus:ring-[#C0A062] shadow-sm">
                                    </div>
                                </div>

                                <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200 flex items-start gap-3 mt-6">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm text-yellow-800 leading-relaxed">
                                        <strong>Perhatian:</strong> Pendaftaran On The Spot ini tidak mencakup fasilitas akomodasi/kamar hotel.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($currentStep == 3)
                        <div class="animate-fade-in-up">
                            <div class="mb-8 border-b border-gray-200 pb-4">
                                <h2 class="text-xl font-black text-[#1B1B1B] uppercase tracking-wide">Kelas Tambahan & Dokumen</h2>
                            </div>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Kelas Tambahan (Opsional)</label>
                                    @foreach($this->additionalClassesList as $class)
                                        <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg mb-3 cursor-pointer hover:bg-gray-50 hover:border-[#C0A062] transition shadow-sm">
                                            <input type="radio" wire:model="selected_class" value="{{ $class->id }}" class="w-5 h-5 text-[#C0A062] focus:ring-[#C0A062]">
                                            <span class="text-sm text-gray-900 font-bold">{{ $class->name }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Surat Tugas / Mandat (Opsional)</label>
                                    <input type="file" wire:model="mandate_letter" class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#C0A062] transition file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#C0A062] file:text-white hover:file:bg-[#a88a53]">
                                    @error('mandate_letter') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <label class="flex items-start gap-3 cursor-pointer bg-blue-50/50 p-4 rounded-lg border border-blue-100 mt-4">
                                    <input type="checkbox" wire:model="consent_data_use" class="mt-0.5 w-5 h-5 text-[#C0A062] focus:ring-[#C0A062] rounded">
                                    <span class="text-sm text-gray-700 font-medium leading-relaxed">Saya setuju data ini digunakan untuk keperluan administrasi dan publikasi acara GIHES 2026.</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    @if ($currentStep == 4)
                        <div class="animate-fade-in-up" x-data="{ copiedRek: false }">
                            <div class="mb-8 border-b border-gray-200 pb-4 text-center">
                                <h2 class="text-xl font-black text-[#1B1B1B] uppercase tracking-wide">Pembayaran Tiket OTS</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                                <!-- Box Total Biaya (Kiri) -->
                                <div class="bg-gray-50 border border-gray-200 p-8 rounded-xl text-center shadow-sm flex flex-col justify-center items-center">
                                    <p class="text-gray-500 font-bold tracking-widest uppercase mb-2">Total Biaya (Tanpa Kamar)</p>
                                    <p class="text-5xl font-black text-[#C0A062] my-4">Rp 2.000.000</p>
                                    <p class="text-sm text-gray-600 bg-white inline-block px-4 py-2 rounded-full border border-gray-200 mt-2">
                                        Pembayaran dilakukan secara tunai/transfer di meja kasir.
                                    </p>
                                </div>

                                <!-- Box Payment Instructions (Kanan) -->
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
                            <button type="button" wire:click="previousStep" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition shadow-sm">
                                Kembali
                            </button>
                        @else
                            <div></div> 
                        @endif

                        @if ($currentStep < $totalSteps)
                            <button type="submit" class="px-8 py-3 bg-[#12241C] text-white rounded-lg font-bold hover:bg-[#1a3328] transition shadow-md flex items-center gap-2">
                                Lanjut
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        @else
                            <button type="button" wire:click="submitFinal" class="px-8 py-3 bg-[#C0A062] text-[#12241C] rounded-lg font-black uppercase tracking-widest hover:bg-[#a3854d] transition shadow-lg flex items-center gap-2">
                                <span wire:loading.remove wire:target="submitFinal">Selesaikan Pendaftaran</span>
                                <span wire:loading wire:target="submitFinal">Menyimpan...</span>
                            </button>
                        @endif
                    </div>

                </form>
            </div>
        @endif
    </div>
    
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