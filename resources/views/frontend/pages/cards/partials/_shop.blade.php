@if ($user->shop && $user->shop->products->isNotEmpty())
@php
    $whatsappPhone = $user->shop->phone ?? $user->phone ?? null;
    $whatsappNumber = $whatsappPhone ? preg_replace('/\D/', '', $whatsappPhone) : '';
@endphp
<style>
.profile-shop { margin:2rem auto; padding:0 1rem; max-width:900px; }
.profile-shop-inner { background:rgba(0,0,0,.03); border-radius:16px; padding:1.5rem; }
.profile-shop-title { font-size:1.35rem; font-weight:700; margin-bottom:1.25rem; text-align:center; }
.profile-shop-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:1rem; }
.profile-shop-product { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.06); transition:transform .2s; display:flex; flex-direction:column; }
.profile-shop-product:hover { transform:translateY(-2px); }
.profile-shop-product img { width:100%; aspect-ratio:1; object-fit:cover; display:block; }
.profile-shop-product-info { padding:.75rem; flex:1; display:flex; flex-direction:column; }
.profile-shop-product-info h3 { font-size:.9rem; font-weight:600; margin:0 0 .25rem; line-height:1.3; }
.profile-shop-price { font-size:.85rem; color:#555; margin:0 0 .5rem; }
.profile-shop-old { text-decoration:line-through; color:#999; margin-right:.35rem; }
.profile-shop-add { width:100%; padding:.5rem .75rem; font-size:.8rem; font-weight:600; border:1px solid #0d6efd; background:#fff; color:#0d6efd; border-radius:8px; cursor:pointer; transition:all .2s; margin-top:auto; }
.profile-shop-add:hover { background:#0d6efd; color:#fff; }
.profile-shop-add.in-cart { background:#198754; border-color:#198754; color:#fff; }
.profile-shop-cart-bar { position:fixed; bottom:0; left:0; right:0; background:#fff; border-top:2px solid #0d6efd; padding:.75rem 1rem; display:none; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; box-shadow:0 -4px 20px rgba(0,0,0,.1); z-index:1000; }
.profile-shop-cart-bar.visible { display:flex; }
.profile-shop-cart-summary { font-size:.9rem; font-weight:600; }
.profile-shop-cart-open-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem 1rem; background:#0d6efd; color:#fff; border:none; border-radius:8px; font-weight:600; font-size:.9rem; cursor:pointer; }
.profile-shop-cart-open-btn:hover { background:#0b5ed7; color:#fff; }
.profile-shop-cart-wa { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem 1rem; background:#25D366; color:#fff; border:none; border-radius:8px; font-weight:600; font-size:.9rem; text-decoration:none; cursor:pointer; }
.profile-shop-cart-wa:hover { color:#fff; background:#20bd5a; }
.profile-shop-cart-wa:disabled { opacity:.6; cursor:not-allowed; }
/* Sidebar basket */
.profile-shop-cart-overlay { position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:1100; opacity:0; visibility:hidden; transition:opacity .25s, visibility .25s; }
.profile-shop-cart-overlay.open { opacity:1; visibility:visible; }
.profile-shop-cart-sidebar { position:fixed; top:0; right:0; width:100%; max-width:360px; height:100%; background:#fff; box-shadow:-4px 0 24px rgba(0,0,0,.15); z-index:1101; display:flex; flex-direction:column; transform:translateX(100%); transition:transform .3s ease; }
.profile-shop-cart-sidebar.open { transform:translateX(0); }
.profile-shop-cart-sidebar-header { padding:1rem 1.25rem; border-bottom:1px solid #eee; display:flex; align-items:center; justify-content:space-between; }
.profile-shop-cart-sidebar-title { font-size:1.1rem; font-weight:700; margin:0; }
.profile-shop-cart-sidebar-close { width:36px; height:36px; border:none; background:#f0f0f0; border-radius:8px; cursor:pointer; font-size:1.25rem; line-height:1; color:#333; }
.profile-shop-cart-sidebar-close:hover { background:#e0e0e0; }
.profile-shop-cart-sidebar-list { flex:1; overflow:auto; padding:1rem 1.25rem; }
.profile-shop-cart-sidebar-item { display:flex; gap:.75rem; padding:.75rem 0; border-bottom:1px solid #f0f0f0; align-items:flex-start; }
.profile-shop-cart-sidebar-item:last-child { border-bottom:none; }
.profile-shop-cart-sidebar-item-img { width:56px; height:56px; border-radius:8px; object-fit:cover; flex-shrink:0; }
.profile-shop-cart-sidebar-item-body { flex:1; min-width:0; }
.profile-shop-cart-sidebar-item-name { font-size:.9rem; font-weight:600; margin:0 0 .25rem; }
.profile-shop-cart-sidebar-item-meta { font-size:.8rem; color:#666; margin:0; }
.profile-shop-cart-sidebar-item-actions { display:flex; align-items:center; gap:.35rem; margin-top:.35rem; }
.profile-shop-cart-sidebar-item-qty { font-size:.85rem; font-weight:600; min-width:1.5rem; }
.profile-shop-cart-sidebar-item-remove { width:28px; height:28px; border:none; background:#fee; color:#c00; border-radius:6px; cursor:pointer; font-size:1rem; line-height:1; }
.profile-shop-cart-sidebar-item-remove:hover { background:#fcc; }
.profile-shop-cart-sidebar-footer { padding:1rem 1.25rem; border-top:2px solid #eee; }
.profile-shop-cart-sidebar-total { font-size:1rem; font-weight:700; margin-bottom:.75rem; }
.profile-shop-cart-sidebar-empty { padding:2rem 1.25rem; text-align:center; color:#888; font-size:.9rem; }
</style>
<section class="profile-shop" id="profile-shop" data-wa-number="{{ $whatsappNumber }}" data-shop-name="{{ $user->shop->name ?: 'Shop' }}">
    <div class="profile-shop-inner">
        <h2 class="profile-shop-title">{{ $user->shop->name ?: 'Our Shop' }}</h2>
        <div class="profile-shop-grid">
            @foreach ($user->shop->products as $product)
                @php
                    $price = $product->sale_price ?: $product->original_price;
                @endphp
                @php $imgSrc = $product->image ? asset($product->image) : 'https://via.placeholder.com/300x300?text=No+image'; @endphp
                <div class="profile-shop-product" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $price }}" data-product-image="{{ $imgSrc }}">
                    <img src="{{ $imgSrc }}" alt="{{ $product->name }}" loading="lazy">
                    <div class="profile-shop-product-info">
                        <h3>{{ Str::limit($product->name, 30) }}</h3>
                        <p class="profile-shop-price">
                            @if ($product->sale_price)
                                <span class="profile-shop-old">{{ $product->original_price }}</span> {{ $product->sale_price }}
                            @else
                                {{ $product->original_price ?? '—' }}
                            @endif
                        </p>
                        <button type="button" class="profile-shop-add" data-action="add">Add to cart</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="profile-shop-cart-overlay" id="profile-shop-cart-overlay" aria-hidden="true"></div>
<div class="profile-shop-cart-sidebar" id="profile-shop-cart-sidebar" aria-label="Cart">
    <div class="profile-shop-cart-sidebar-header">
        <h2 class="profile-shop-cart-sidebar-title">Your basket</h2>
        <button type="button" class="profile-shop-cart-sidebar-close" id="profile-shop-cart-close" aria-label="Close cart">&times;</button>
    </div>
    <div class="profile-shop-cart-sidebar-list" id="profile-shop-cart-sidebar-list">
        <div class="profile-shop-cart-sidebar-empty" id="profile-shop-cart-empty">No items in cart</div>
    </div>
    <div class="profile-shop-cart-sidebar-footer" id="profile-shop-cart-sidebar-footer" style="display:none;">
        <p class="profile-shop-cart-sidebar-total" id="profile-shop-cart-sidebar-total">Total: 0</p>
        @if($whatsappNumber)
        <a href="#" class="profile-shop-cart-wa" id="profile-shop-cart-wa" style="width:100%; justify-content:center; box-sizing:border-box;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Place order via WhatsApp
        </a>
        @endif
    </div>
</div>

<div class="profile-shop-cart-bar" id="profile-shop-cart-bar">
    <span class="profile-shop-cart-summary" id="profile-shop-cart-summary">0 items in cart</span>
    <div style="display:flex; align-items:center; gap:.5rem;">
        <button type="button" class="profile-shop-cart-open-btn" id="profile-shop-cart-open-btn" style="display:none;">View basket</button>
        @if($whatsappNumber)
        <a href="#" class="profile-shop-cart-wa" id="profile-shop-cart-wa-bar" target="_blank" rel="noopener noreferrer" style="display:none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Place order via WhatsApp
        </a>
        @endif
    </div>
</div>

<script>
(function() {
    var cart = {};
    var shopEl = document.getElementById('profile-shop');
    var bar = document.getElementById('profile-shop-cart-bar');
    var summary = document.getElementById('profile-shop-cart-summary');
    var waNumber = shopEl && shopEl.getAttribute('data-wa-number');
    var shopName = shopEl && shopEl.getAttribute('data-shop-name');
    var overlay = document.getElementById('profile-shop-cart-overlay');
    var sidebar = document.getElementById('profile-shop-cart-sidebar');
    var sidebarList = document.getElementById('profile-shop-cart-sidebar-list');
    var sidebarEmpty = document.getElementById('profile-shop-cart-empty');
    var sidebarFooter = document.getElementById('profile-shop-cart-sidebar-footer');
    var sidebarTotal = document.getElementById('profile-shop-cart-sidebar-total');
    var openBtn = document.getElementById('profile-shop-cart-open-btn');
    var closeBtn = document.getElementById('profile-shop-cart-close');
    var waBtnSidebar = document.getElementById('profile-shop-cart-wa');
    var waBtnBar = document.getElementById('profile-shop-cart-wa-bar');

    function openSidebar() {
        if (overlay) overlay.classList.add('open');
        if (sidebar) { sidebar.classList.add('open'); sidebar.setAttribute('aria-hidden', 'false'); }
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        if (overlay) overlay.classList.remove('open');
        if (sidebar) { sidebar.classList.remove('open'); sidebar.setAttribute('aria-hidden', 'true'); }
        document.body.style.overflow = '';
    }

    function renderSidebarList() {
        var count = 0, total = 0;
        for (var id in cart) count += cart[id].qty;
        for (var id in cart) total += cart[id].qty * (parseFloat(cart[id].price) || 0);

        if (count === 0) {
            sidebarEmpty.style.display = 'block';
            sidebarFooter.style.display = 'none';
            sidebarList.querySelectorAll('.profile-shop-cart-sidebar-item').forEach(function(el) { el.remove(); });
            return;
        }
        sidebarEmpty.style.display = 'none';
        sidebarFooter.style.display = 'block';
        sidebarTotal.textContent = 'Total: ' + total;
        var existing = sidebarList.querySelectorAll('.profile-shop-cart-sidebar-item');
        existing.forEach(function(el) { el.remove(); });
        for (var id in cart) {
            var item = cart[id];
            var subtotal = item.qty * (parseFloat(item.price) || 0);
            var div = document.createElement('div');
            div.className = 'profile-shop-cart-sidebar-item';
            div.setAttribute('data-product-id', id);
            div.innerHTML = '<img class="profile-shop-cart-sidebar-item-img" src="' + (item.image || '') + '" alt="">' +
                '<div class="profile-shop-cart-sidebar-item-body">' +
                '<p class="profile-shop-cart-sidebar-item-name">' + escapeHtml(item.name) + '</p>' +
                '<p class="profile-shop-cart-sidebar-item-meta">' + (item.price || '') + ' each</p>' +
                '<div class="profile-shop-cart-sidebar-item-actions">' +
                '<span class="profile-shop-cart-sidebar-item-qty">Qty: ' + item.qty + '</span>' +
                '<button type="button" class="profile-shop-cart-sidebar-item-remove" data-product-id="' + id + '" aria-label="Remove one">−</button>' +
                '</div></div>';
            sidebarList.insertBefore(div, sidebarEmpty);
        }
        sidebarList.querySelectorAll('.profile-shop-cart-sidebar-item-remove').forEach(function(btn) {
            btn.onclick = function() {
                var id = btn.getAttribute('data-product-id');
                if (cart[id]) {
                    cart[id].qty--;
                    if (cart[id].qty <= 0) delete cart[id];
                }
                updateCartUI();
            };
        });
    }
    function escapeHtml(s) {
        var div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    function updateCartUI() {
        var total = 0, count = 0;
        for (var id in cart) { count += cart[id].qty; total += cart[id].qty * (parseFloat(cart[id].price) || 0); }
        if (bar) {
            if (count > 0) {
                bar.classList.add('visible');
                summary.textContent = count + ' item' + (count !== 1 ? 's' : '') + ' in cart';
                if (openBtn) openBtn.style.display = 'inline-flex';
                if (waBtnBar) waBtnBar.style.display = 'inline-flex';
            } else {
                bar.classList.remove('visible');
                if (openBtn) openBtn.style.display = 'none';
                if (waBtnBar) waBtnBar.style.display = 'none';
            }
        }
        document.querySelectorAll('.profile-shop-product').forEach(function(row) {
            var id = row.getAttribute('data-product-id');
            var btn = row.querySelector('.profile-shop-add');
            if (btn && cart[id]) {
                btn.textContent = 'In cart (' + cart[id].qty + ')';
                btn.classList.add('in-cart');
            } else if (btn) {
                btn.textContent = 'Add to cart';
                btn.classList.remove('in-cart');
            }
        });
        renderSidebarList();
    }

    function buildWhatsAppMessage() {
        var lines = ['Hi! I would like to place an order from *' + (shopName || 'your shop') + '*', ''];
        var total = 0;
        for (var id in cart) {
            var item = cart[id];
            var price = parseFloat(item.price) || 0;
            var subtotal = item.qty * price;
            total += subtotal;
            lines.push('- ' + item.name + ' x ' + item.qty + ' — ' + (item.price || '') + (item.qty > 1 ? ' = ' + subtotal : ''));
        }
        lines.push('');
        lines.push('*Total: ' + total + '*');
        return lines.join("\n");
    }

    function openWa() {
        var count = 0;
        for (var id in cart) count += cart[id].qty;
        if (count === 0 || !waNumber) return;
        var text = encodeURIComponent(buildWhatsAppMessage());
        window.open('https://wa.me/' + waNumber + '?text=' + text, '_blank', 'noopener,noreferrer');
    }

    if (shopEl) {
        shopEl.addEventListener('click', function(e) {
            var btn = e.target.closest('.profile-shop-add');
            if (!btn) return;
            var row = btn.closest('.profile-shop-product');
            var id = row.getAttribute('data-product-id');
            var name = row.getAttribute('data-product-name');
            var price = row.getAttribute('data-product-price') || '';
            var image = row.getAttribute('data-product-image') || '';
            if (!cart[id]) cart[id] = { name: name, price: price, image: image, qty: 0 };
            cart[id].qty += 1;
            updateCartUI();
        });
    }

    if (overlay) overlay.addEventListener('click', closeSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (openBtn) openBtn.addEventListener('click', openSidebar);
    if (waBtnSidebar) waBtnSidebar.addEventListener('click', function(e) { e.preventDefault(); openWa(); });
    if (waBtnBar) waBtnBar.addEventListener('click', function(e) { e.preventDefault(); openWa(); });

    updateCartUI();
})();
</script>
@endif
