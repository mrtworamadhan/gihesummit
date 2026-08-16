<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::auth')] class extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/panel');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }
};
?>

<div class="max-w-md mx-auto w-full mt-10 md:mt-16">
    <div class="bg-white rounded-md shadow-xl border border-[#E5E4DF] overflow-hidden">
        
        <div class="bg-[#12241C] p-6 md:p-8 text-center">
            <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-widest">Welcome Back</h2>
            <p class="text-[#C0A062] mt-2 text-sm">Participant Portal GIHES 2026</p>
        </div>

        <div class="p-6 md:p-8">
            <form wire:submit.prevent="login" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
                    <input type="email" wire:model="email" class="w-full border border-gray-300 rounded px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="delegate@institution.com">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" wire:model="password" class="w-full border border-gray-300 rounded px-4 py-3 focus:ring-2 focus:ring-[#C0A062] focus:border-transparent outline-none transition-all" placeholder="Enter your password">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="remember" class="rounded text-[#5A6446] focus:ring-[#C0A062] border-gray-300">
                        <span class="text-sm text-gray-600 font-medium">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-[#5A6446] hover:text-[#12241C] transition-colors">Forgot Password?</a>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#C0A062] hover:bg-[#a3854d] text-[#12241C] font-black tracking-widest uppercase py-4 rounded-sm shadow-lg hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-2">
                        <svg wire:loading wire:target="login" class="animate-spin -ml-1 mr-2 h-5 w-5 text-[#12241C]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Log In</span>
                    </button>
                    
                    <p class="text-center mt-6 text-sm text-gray-600 font-medium">
                        Don't have an account? <a href="/register" class="text-[#5A6446] hover:text-[#12241C] font-bold hover:underline">Register your delegation</a>
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>