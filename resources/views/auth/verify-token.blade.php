@extends('layouts.auth')

@section('title', 'Verifikasi Kode')
@section('breadcrumb', 'Verifikasi Kode')

@section('content')
    @php
        $isRegistrationVerification = ($verificationContext ?? null) === 'registration';
        $formAction = $isRegistrationVerification ? route('verification.verify') : route('password.verify.post');
        $emailValue = old('email', $email ?? '');
        $tokenFieldName = $isRegistrationVerification ? 'otp' : 'token';
        $secondsLeft = 0;

        if ($isRegistrationVerification && isset($verification) && $verification->expires_at) {
            $secondsLeft = max(0, now()->diffInSeconds($verification->expires_at, false));
        }
    @endphp

    <div class="text-center mb-6">
        <p class="text-text/80 text-sm">
            Kami telah mengirimkan kode verifikasi 6 digit ke email:<br>
            <strong class="text-primary">{{ $emailValue }}</strong>
        </p>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if ($isRegistrationVerification && ($isLocked ?? false))
        <div class="p-4 bg-yellow-50 text-yellow-800 border border-yellow-200 rounded-lg text-sm mb-6 text-center">
            <svg class="w-8 h-8 mx-auto mb-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $lockMessage }}
        </div>
    @else
        <form method="POST" action="{{ $formAction }}" class="mb-6">
            @csrf
            <input type="hidden" name="email" value="{{ $emailValue }}">

            <div class="mb-6">
                <label for="token" class="block mb-2 font-medium text-text text-center">Kode Verifikasi</label>
                <input
                    type="text"
                    id="token"
                    name="{{ $tokenFieldName }}"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    inputmode="numeric"
                    class="w-full p-4 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent text-center text-3xl font-bold tracking-[0.5em] bg-white"
                    required
                    autofocus
                    placeholder="000000"
                >
            </div>

            <button type="submit" class="w-full py-3 bg-primary text-background font-medium rounded-lg hover:bg-accent transition-colors">
                Verifikasi Kode
            </button>
        </form>
    @endif

    <div class="text-center mt-6 pt-6 border-t border-secondary/30 text-text/80 text-sm">
        @if ($isRegistrationVerification)
            <div id="countdown-wrapper" class="{{ $secondsLeft > 0 ? '' : 'hidden' }}">
                Kirim ulang kode OTP dalam <span id="timer" class="font-bold text-primary">{{ $secondsLeft }}</span> detik
            </div>

            <form id="resend-form" method="POST" action="{{ route('verification.resend') }}" class="{{ $secondsLeft > 0 ? 'hidden' : '' }}">
                @csrf
                <span>Tidak menerima kode? </span>
                <button type="submit" class="text-accent font-medium hover:underline focus:outline-none {{ ($isLocked ?? false) ? 'cursor-not-allowed opacity-50' : '' }}" {{ ($isLocked ?? false) ? 'disabled' : '' }}>
                    Kirim ulang
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('password.resend') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $emailValue }}">
                <span>Tidak menerima kode? </span>
                <button type="submit" class="text-accent font-medium hover:underline focus:outline-none">
                    Kirim ulang
                </button>
            </form>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tokenInput = document.getElementById('token');

                if (tokenInput) {
                    tokenInput.addEventListener('input', function () {
                        this.value = this.value.replace(/\D/g, '').slice(0, 6);
                    });
                }

                let secondsLeft = {{ $secondsLeft }};
                const timerElement = document.getElementById('timer');
                const countdownWrapper = document.getElementById('countdown-wrapper');
                const resendForm = document.getElementById('resend-form');

                if (secondsLeft > 0) {
                    const interval = setInterval(function () {
                        secondsLeft--;
                        if (timerElement) {
                            timerElement.textContent = secondsLeft;
                        }

                        if (secondsLeft <= 0) {
                            clearInterval(interval);
                            if (countdownWrapper) countdownWrapper.classList.add('hidden');
                            if (resendForm) resendForm.classList.remove('hidden');
                        }
                    }, 1000);
                }
            });
        </script>
    @endpush
@endsection
