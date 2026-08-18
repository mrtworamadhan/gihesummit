<?php

use App\Models\User;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::auth')] class extends Component
{
    public $name, $email, $whatsapp, $institution_name, $nationality, $password, $password_confirmation;
    public $phone_code = '62';
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'whatsapp' => 'required|string|min:10|max:15|unique:users,whatsapp',
            'institution_name' => 'required|string|max:255',
            'nationality' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ];
    }

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

    public function register()
    {
        $this->validate();
        $cleanWhatsapp = ltrim($this->whatsapp, '0');
        $fullWhatsappNumber = $this->phone_code . $cleanWhatsapp;

        // 3. (Opsional) Validasi ulang memastikan nomor yang sudah digabung ini belum terdaftar
        $exists = User::where('whatsapp', $fullWhatsappNumber)->exists();
        if ($exists) {
            $this->addError('whatsapp', 'This WhatsApp number is already registered.');
            return;
        }

        // 1. Buat User Baru
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'whatsapp' => $fullWhatsappNumber,
            'institution_name' => $this->institution_name, 
            'nationality' => $this->nationality,
            'password' => Hash::make($this->password),
            'role' => 'participant',
        ]);

        // 2. TODO: Trigger API Fonnte di sini
        $loginUrl = url('/login');
        Http::withHeaders([
            'Authorization' => 'eYx7Pa6K2xiSE4s9aQxo' 
        ])->post('https://api.fonnte.com/send', [
            'target' => $this->whatsapp,
            'message' => "Hello *{$this->name}*,\n\nWelcome to GIHES 2026! Your account has been successfully created.\n\nPlease log in to the Participant Portal using your registered email (*{$this->email}*) to complete your profile, logistics, and accommodation details.\n\nAccess the portal directly via this link:\n{$loginUrl}\n\nIf you need any assistance, feel free to reply to this message.\n\nBest regards,\n*GIHES 2026 Organizing Committee*",
        ]);

        Auth::login($user);

        return redirect()->to('/panel'); 
    }
};
?>

<div class="max-w-3xl mx-auto w-full mt-4 md:mt-10">
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r shadow-sm transition-all">
            <div class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">
                        Registration Failed / Pendaftaran Gagal
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-md shadow-xl border border-[#E5E4DF] overflow-hidden">
        
        <div class="bg-[#12241C] p-6 md:p-8 text-center">
            <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-widest">Create Account</h2>
            <p class="text-[#C0A062] mt-2 text-sm md:text-base">Step 1: Set up your delegate profile</p>
        </div>

        <div class="p-6 md:p-8">
            <form wire:submit.prevent="register" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Full Name (as per ID) *</label>
                        <input type="text" wire:model="name" class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="M Ramadhan">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Active WhatsApp Number *</label>
                        <div class="flex">
                            <select wire:model.live="phone_code" class="w-32 border border-gray-300 border-r-0 rounded-l px-2 py-2.5 bg-gray-50 focus:ring-2 focus:ring-[#C0A062] outline-none transition-all text-sm font-medium">
                                @foreach($this->countries as $country)
                                    <option value="{{ $country->phonecode }}">
                                        ({{ $country->iso }}) +{{ $country->phonecode }} 
                                    </option>
                                @endforeach
                            </select>
                            
                            <input type="text" wire:model="whatsapp" class="flex-grow border border-gray-300 rounded-r px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="8123456789">
                        </div>
                        <span class="text-gray-400 text-[10px] mt-1 block">Omit leading zero (e.g., 812...)</span>
                        @error('whatsapp') <span class="text-red-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Institution Name *</label>
                        <input type="text" wire:model="institution_name" class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="Pondok Modern / University">
                        @error('institution_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nationality *</label>
                        <select wire:model.live="nationality" class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all bg-white">
                            <option value="">-- Select Country --</option>
                            @foreach($this->countries as $country)
                                <option value="{{ $country->nicename }}">{{ $country->nicename }}</option>
                            @endforeach
                        </select>
                        @error('nationality') <span class="text-red-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="text-sm font-black tracking-widest text-[#5A6446] uppercase mb-4">Login Credentials</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email Address *</label>
                            <input type="email" wire:model="email" class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="delegate@institution.com">
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div x-data="{ show: false }">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Password *</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" wire:model="password" class="w-full border border-gray-300 rounded pl-4 pr-10 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="Min. 8 characters">
                                    
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-[#C0A062] focus:outline-none transition-colors">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.5-2.5m1.5-2.5A10.046 10.046 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.04 10.04 0 01-1.5 2.5m-1.5 2.5A9.95 9.95 0 0112 19m-3-3l6-6" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div x-data="{ show: false }">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Confirm Password *</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" class="w-full border border-gray-300 rounded pl-4 pr-10 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="Repeat password">
                                    
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-[#C0A062] focus:outline-none transition-colors">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.5-2.5m1.5-2.5A10.046 10.046 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.04 10.04 0 01-1.5 2.5m-1.5 2.5A9.95 9.95 0 0112 19m-3-3l6-6" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-[#C0A062] hover:bg-[#a3854d] text-[#12241C] font-black tracking-widest uppercase py-4 rounded-sm shadow-lg hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-2">
                        <svg wire:loading wire:target="register" class="animate-spin -ml-1 mr-2 h-5 w-5 text-[#12241C]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Register Account</span>
                    </button>
                    
                    <p class="text-center mt-6 text-sm text-gray-600 font-medium">
                        Already registered? <a href="/login" class="text-[#5A6446] hover:text-[#12241C] font-bold hover:underline">Log in to Participant Portal</a>
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>