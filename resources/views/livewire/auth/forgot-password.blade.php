 <div class="flex flex-col gap-6">
     <x-auth-header
         :title="__('Forgot password')"
         :description="__('Enter your email to receive a password reset link')"
     />

     <!-- Session Status -->
     <x-auth-session-status
         class="text-center"
         :status="session('status')"
     />

     <form
         class="flex flex-col gap-6"
         method="POST"
         wire:submit="sendPasswordResetLink"
     >
         <!-- Email Address -->
         <flux:input
             type="email"
             wire:model="email"
             :label="__('Email Address')"
             required
             autofocus
             placeholder="email@example.com"
         />

         <flux:button
             class="w-full"
             type="submit"
             variant="primary"
         >{{ __('Email password reset link') }}</flux:button>
     </form>

     <div class="space-x-1 text-center text-sm text-zinc-400 rtl:space-x-reverse">
         <span>{{ __('Or, return to') }}</span>
         <flux:link
             :href="route('login')"
             wire:navigate
         >{{ __('log in') }}</flux:link>
     </div>
 </div>
