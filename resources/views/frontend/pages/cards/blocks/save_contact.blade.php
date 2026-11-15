<section class="save-contact-block text-center py-3">
    <a href="{{ route('downloadVCard', $user->username) }}" class="btn btn-primary btn-lg">
        <i class="fas fa-download me-2"></i>Save contact
    </a>
    @include('frontend.pages.cards.partials._nfc')
</section>
