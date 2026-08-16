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
            'Authorization' => 'iMuaL2ReDTbeswcWmrfN' 
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
                            <select wire:model="phone_code" class="w-32 border border-gray-300 border-r-0 rounded-l px-2 py-2.5 bg-gray-50 focus:ring-2 focus:ring-[#C0A062] outline-none transition-all text-sm font-medium">
                                @foreach($this->countries as $country)
                                    <option value="{{ $country->phonecode }}">
                                        ({{ $country->iso }}) +{{ $country->phonecode }} 
                                    </option>
                                @endforeach
                            </select>
                            
                            <input type="text" wire:model="whatsapp" class="flex-grow border border-gray-300 rounded-r px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="8123456789">
                        </div>
                        <span class="text-gray-400 text-[10px] mt-1 block">Omit leading zero (e.g., 812...)</span>
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
                        <select wire:model="nationality" class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all bg-white">
                            <option value="">-- Select Country --</option>
                            @foreach($this->countries as $country)
                                <option value="{{ $country->nicename }}">{{ $country->nicename }}</option>
                            @endforeach
                        </select>
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
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Password *</label>
                                <input type="password" wire:model="password" class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="Min. 8 characters">
                                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Confirm Password *</label>
                                <input type="password" wire:model="password_confirmation" class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="Repeat password">
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