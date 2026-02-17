{{-- NFC: Write contact URL to an NFC tag (Chrome Android). When someone taps the tag, they get the vCard download. --}}
<div class="profile-nfc-wrap" id="profile-nfc-wrap" data-vcard-url="{{ url(route('downloadVCard', $user->username)) }}" style="display:none;">
    <button type="button" class="profile-nfc-btn" id="profile-nfc-write-btn" aria-label="Write contact to NFC tag">
        <i class="fas fa-broadcast-tower"></i>
        <span>Write to NFC tag</span>
    </button>
    <p class="profile-nfc-hint" id="profile-nfc-hint">Tap your phone to an NFC tag to save this contact for others.</p>
    <p class="profile-nfc-status" id="profile-nfc-status" role="status" aria-live="polite"></p>
</div>
<style>
.profile-nfc-wrap { margin-top: 1rem; padding: 1rem; background: rgba(0,0,0,.04); border-radius: 12px; text-align: center; }
.profile-nfc-btn { display: inline-flex; align-items: center; gap: .5rem; padding: .65rem 1.25rem; background: #333; color: #fff; border: none; border-radius: 999px; font-weight: 600; font-size: .9rem; cursor: pointer; min-height: 44px; }
.profile-nfc-btn:hover { background: #555; color: #fff; }
.profile-nfc-btn:disabled { opacity: .6; cursor: not-allowed; }
.profile-nfc-hint { font-size: .8rem; color: #666; margin: .5rem 0 0; }
.profile-nfc-status { font-size: .85rem; margin: .35rem 0 0; min-height: 1.2em; }
.profile-nfc-status.success { color: #0a0; }
.profile-nfc-status.error { color: #c00; }
</style>
<script>
(function() {
    var wrap = document.getElementById('profile-nfc-wrap');
    var btn = document.getElementById('profile-nfc-write-btn');
    var status = document.getElementById('profile-nfc-status');
    if (!wrap || !btn) return;
    var vcardUrl = wrap.getAttribute('data-vcard-url');
    if (!vcardUrl) return;
    if (typeof NDEFReader === 'undefined') return;
    wrap.style.display = 'block';
    btn.addEventListener('click', function() {
        status.textContent = '';
        status.className = 'profile-nfc-status';
        btn.disabled = true;
        status.textContent = 'Hold your phone near an NFC tag…';
        var ndef = new NDEFReader();
        ndef.write({
            records: [{ recordType: 'url', data: vcardUrl }]
        }).then(function() {
            status.textContent = 'Done! The tag is ready. Anyone who taps it can save this contact.';
            status.className = 'profile-nfc-status success';
        }).catch(function(err) {
            status.textContent = err.message || 'Could not write. Use an empty NFC tag and try again.';
            status.className = 'profile-nfc-status error';
        }).finally(function() {
            btn.disabled = false;
        });
    });
})();
</script>
