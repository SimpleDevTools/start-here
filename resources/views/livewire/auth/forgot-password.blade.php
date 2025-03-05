 <div class="flex flex-col gap-6">
     <x-auth-header
         title="Forgot password"
         description="Enter your email to receive a password reset link"
     />

     <!-- Session Status -->
     <x-auth-session-status
         class="text-center"
         :status="session('status')"
     />

     <form
         class="flex flex-col gap-6"
         wire:submit="sendPasswordResetLink"
     >
         <!-- Email Address -->
         <flux:input
             name="email"
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

     <div class="space-x-1 text-center text-sm text-zinc-400">
         Or, return to
         <flux:link
             :href="route('login')"
             wire:navigate
         >log in</flux:link>
     </div>
 </div>
