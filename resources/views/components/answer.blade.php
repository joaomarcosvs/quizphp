<div class="col-6 text-center">
    <a href="{{ route('answer', Crypt::encryptString($capital)) }}" class="text-decoration-none">
        <p class="response-option">{{ $capital }}</p>
    </a>
</div>
