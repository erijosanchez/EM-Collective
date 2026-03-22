<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>@yield('subject', 'EM Collective')</title>
<style>
/* ── Reset ── */
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
body { margin:0; padding:0; background:#EDE8DF; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
img { border:0; outline:none; text-decoration:none; -ms-interpolation-mode:bicubic; display:block; }
a { text-decoration:none; }

/* ── Animations (Gmail/Apple Mail) ── */
@keyframes fadeSlideUp {
  from { opacity:0; transform:translateY(24px); }
  to   { opacity:1; transform:translateY(0); }
}
@keyframes shimmerBtn {
  0%   { background-position: -200% center; }
  100% { background-position:  200% center; }
}
@keyframes pulseGlow {
  0%, 100% { box-shadow: 0 0 0 0 rgba(196,113,74,0.4); }
  50%       { box-shadow: 0 0 0 8px rgba(196,113,74,0); }
}
@keyframes scaleIn {
  from { opacity:0; transform:scale(0.95); }
  to   { opacity:1; transform:scale(1); }
}
@keyframes spin {
  from { transform:rotate(0deg); }
  to   { transform:rotate(360deg); }
}

.anim-fade   { animation: fadeSlideUp 0.7s ease both; }
.anim-fade-2 { animation: fadeSlideUp 0.7s 0.15s ease both; }
.anim-fade-3 { animation: fadeSlideUp 0.7s 0.3s ease both; }
.anim-scale  { animation: scaleIn 0.5s ease both; }

.btn-shimmer {
  background: linear-gradient(90deg, #1A1A18 0%, #3a3a36 25%, #1A1A18 50%, #3a3a36 75%, #1A1A18 100%);
  background-size: 200% auto;
  animation: shimmerBtn 3s linear infinite;
}
.btn-terracota {
  background: linear-gradient(90deg, #B85C38 0%, #9e4a2a 25%, #B85C38 50%, #9e4a2a 75%, #B85C38 100%);
  background-size: 200% auto;
  animation: shimmerBtn 3s linear infinite, pulseGlow 2s ease-in-out infinite;
}

/* ── Layout ── */
.email-outer  { width:100%; background:#EDE8DF; padding:32px 16px; }
.email-wrap   { max-width:600px; margin:0 auto; }

/* Header */
.email-header {
  background:#1A1A18;
  padding:0;
  position:relative;
  overflow:hidden;
}
.header-deco {
  position:relative;
  padding:36px 40px 28px;
  text-align:center;
}
.header-lines {
  display:flex;
  align-items:center;
  justify-content:center;
  gap:12px;
  margin-bottom:16px;
}
.header-line { flex:1; height:1px; background:rgba(245,240,232,0.15); max-width:60px; }
.header-dot  { width:4px; height:4px; background:#B85C38; border-radius:50%; }
.logo-text   { font-family:Georgia,'Times New Roman',serif; color:#F5F0E8; font-size:28px; font-weight:300; letter-spacing:0.3em; text-transform:uppercase; }
.logo-sub    { color:#8A8880; font-size:10px; letter-spacing:0.25em; text-transform:uppercase; margin-top:6px; }

/* Accent strip */
.accent-strip {
  height:4px;
  background:linear-gradient(90deg, #B85C38, #9e4a2a, #B85C38);
  background-size:200% auto;
  animation: shimmerBtn 4s linear infinite;
}

/* Hero banner del email */
.email-hero {
  background:linear-gradient(135deg, #1A1A18 0%, #2d2d2a 60%, #1A1A18 100%);
  padding:36px 40px;
  position:relative;
  overflow:hidden;
}
.email-hero::before {
  content:'';
  position:absolute;
  top:-30px; right:-30px;
  width:160px; height:160px;
  border-radius:50%;
  background:rgba(196,113,74,0.08);
}
.email-hero::after {
  content:'';
  position:absolute;
  bottom:-40px; left:-20px;
  width:120px; height:120px;
  border-radius:50%;
  background:rgba(196,113,74,0.05);
}
.hero-eyebrow { color:#B85C38; font-size:10px; letter-spacing:0.2em; text-transform:uppercase; margin-bottom:10px; }
.hero-title   { font-family:Georgia,'Times New Roman',serif; color:#F5F0E8; font-size:32px; font-weight:300; line-height:1.25; margin-bottom:10px; }
.hero-subtitle { color:#8A8880; font-size:14px; line-height:1.6; }

/* Body */
.email-body { background:#FFFFFF; padding:40px; }

/* Section title */
.section-title {
  font-family:Georgia,serif;
  font-size:18px;
  font-weight:300;
  color:#1A1A18;
  margin-bottom:16px;
  padding-bottom:12px;
  border-bottom:1px solid #EDE8DF;
}

/* Highlight box */
.highlight-box {
  background:linear-gradient(135deg, #FBF8F3 0%, #F5F0E8 100%);
  border-left:3px solid #B85C38;
  padding:20px 24px;
  margin:20px 0;
  position:relative;
}
.order-number-label { font-size:10px; text-transform:uppercase; letter-spacing:0.15em; color:#8A8880; margin-bottom:6px; display:block; }
.order-number-value { font-family:Georgia,serif; font-size:26px; font-weight:300; color:#1A1A18; letter-spacing:0.05em; }

/* Product table */
.product-table { width:100%; }
.product-table th {
  background:#F5F0E8;
  font-size:9px;
  text-transform:uppercase;
  letter-spacing:0.15em;
  color:#8A8880;
  padding:10px 14px;
  font-weight:500;
}
.product-table td {
  padding:14px;
  font-size:13px;
  border-bottom:1px solid #F0EBE3;
  color:#1A1A18;
  vertical-align:middle;
}
.product-table tfoot td {
  font-size:13px;
  border-bottom:none;
  padding:10px 14px;
  color:#4a4a48;
}
.total-row td { font-weight:600; font-size:15px !important; color:#1A1A18 !important; padding-top:14px !important; border-top:2px solid #1A1A18; }
.product-img  { width:52px; height:60px; object-fit:cover; }

/* Address / info grid */
.info-grid { background:#F5F0E8; }
.info-cell { padding:18px 24px; vertical-align:top; }
.info-label { font-size:9px; text-transform:uppercase; letter-spacing:0.15em; color:#8A8880; display:block; margin-bottom:6px; }
.info-value { font-size:13px; line-height:1.6; color:#1A1A18; }

/* Divider */
.divider { height:1px; background:#EDE8DF; margin:24px 0; }

/* CTA Button */
.cta-wrap { text-align:center; padding:28px 0 8px; }
.btn-main {
  display:inline-block;
  padding:16px 48px;
  font-size:11px;
  letter-spacing:0.15em;
  text-transform:uppercase;
  font-weight:600;
  color:#F5F0E8 !important;
  border-radius:0;
  font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;
}

/* Status tracker */
.tracker { padding:24px 0; }
.tracker-steps { display:flex; align-items:center; justify-content:center; }
.tracker-step  { text-align:center; flex:1; }
.tracker-dot-wrap { position:relative; display:flex; justify-content:center; margin-bottom:8px; }
.tracker-dot  { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; color:#fff; }
.dot-done     { background:#B85C38; }
.dot-active   { background:#1A1A18; animation:pulseGlow 2s ease-in-out infinite; }
.dot-pending  { background:#D5CFC6; }
.tracker-line { flex:1; height:2px; background:#EDE8DF; margin-top:-22px; }
.tracker-line.done { background:#B85C38; }
.tracker-label { font-size:9px; text-transform:uppercase; letter-spacing:0.1em; color:#8A8880; }
.tracker-label.active { color:#1A1A18; font-weight:600; }

/* Trust badges */
.trust-row { background:#1A1A18; padding:24px 40px; }
.trust-item { text-align:center; }
.trust-icon { font-size:22px; display:block; margin-bottom:6px; }
.trust-text { color:#8A8880; font-size:10px; letter-spacing:0.1em; text-transform:uppercase; line-height:1.4; }
.trust-val  { color:#F5F0E8; font-size:11px; margin-bottom:2px; font-weight:500; }

/* Footer */
.email-footer { background:#111110; padding:32px 40px; text-align:center; }
.footer-logo  { font-family:Georgia,serif; color:#F5F0E8; font-size:18px; font-weight:300; letter-spacing:0.3em; margin-bottom:16px; }
.footer-links { margin-bottom:16px; }
.footer-links a { color:#B85C38; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; margin:0 10px; }
.social-icons { margin-bottom:20px; }
.social-icons a {
  display:inline-block;
  width:32px; height:32px;
  border-radius:50%;
  background:rgba(245,240,232,0.08);
  margin:0 4px;
  line-height:32px;
  text-align:center;
}
.social-icon { width:16px; height:16px; fill:#8A8880; vertical-align:middle; }
.footer-legal { color:#4a4a48; font-size:10px; line-height:1.6; }
.footer-legal a { color:#8A8880; }

/* Mobile */
@media only screen and (max-width: 600px) {
  .email-body  { padding:24px 20px !important; }
  .email-hero  { padding:28px 20px !important; }
  .header-deco { padding:28px 20px 20px !important; }
  .trust-row   { padding:20px !important; }
  .email-footer{ padding:24px 20px !important; }
  .hero-title  { font-size:24px !important; }
  .info-cell   { display:block !important; width:100% !important; }
  .trust-item  { display:block !important; width:50% !important; margin-bottom:16px; float:left; }
}
</style>
</head>
<body>
<div class="email-outer">
<table class="email-wrap" width="100%" cellpadding="0" cellspacing="0">
<tr><td>

    {{-- ── HEADER ── --}}
    <div class="email-header anim-scale">
        <div class="header-deco">
            <div class="header-lines">
                <div class="header-line"></div>
                <div class="header-dot"></div>
                <div class="header-dot" style="background:#F5F0E8;opacity:0.3"></div>
                <div class="header-dot"></div>
                <div class="header-line"></div>
            </div>
            <div class="logo-text">EM COLLECTIVE</div>
            <div class="logo-sub">Moda editorial para toda la familia</div>
        </div>
    </div>
    <div class="accent-strip"></div>

    {{-- ── HERO del email ── --}}
    <div class="email-hero anim-fade">
        @yield('hero')
    </div>

    {{-- ── BODY ── --}}
    <div class="email-body anim-fade-2">
        @yield('body')
    </div>

    {{-- ── TRACKER DE ESTADO (opcional) ── --}}
    @hasSection('tracker')
    <div style="background:#FAFAF8; padding:24px 40px;">
        @yield('tracker')
    </div>
    @endif

    {{-- ── TRUST BADGES ── --}}
    <div class="trust-row anim-fade-3">
        <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="trust-item" width="25%" style="padding:0 8px;text-align:center">
                <span class="trust-icon">🚚</span>
                <div class="trust-val">Envío Rápido</div>
                <div class="trust-text">A todo el Perú</div>
            </td>
            <td class="trust-item" width="25%" style="padding:0 8px;text-align:center">
                <span class="trust-icon">🔒</span>
                <div class="trust-val">Pago Seguro</div>
                <div class="trust-text">Encriptación SSL</div>
            </td>
            <td class="trust-item" width="25%" style="padding:0 8px;text-align:center">
                <span class="trust-icon">🔄</span>
                <div class="trust-val">Cambios Fáciles</div>
                <div class="trust-text">30 días sin costo</div>
            </td>
            <td class="trust-item" width="25%" style="padding:0 8px;text-align:center">
                <span class="trust-icon">💬</span>
                <div class="trust-val">Soporte 24/7</div>
                <div class="trust-text">WhatsApp directo</div>
            </td>
        </tr>
        </table>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="email-footer">
        <div class="footer-logo">EM COLLECTIVE</div>

        {{-- Social icons --}}
        <div class="social-icons">
            @if(\App\Models\Setting::get('social_instagram'))
            <a href="{{ \App\Models\Setting::get('social_instagram') }}" style="display:inline-block;width:32px;height:32px;background:rgba(245,240,232,0.08);border-radius:50%;margin:0 4px;text-align:center;line-height:36px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#8A8880" style="vertical-align:middle"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
            @endif
            @if(\App\Models\Setting::get('social_facebook'))
            <a href="{{ \App\Models\Setting::get('social_facebook') }}" style="display:inline-block;width:32px;height:32px;background:rgba(245,240,232,0.08);border-radius:50%;margin:0 4px;text-align:center;line-height:36px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#8A8880" style="vertical-align:middle"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            @endif
            @if(\App\Models\Setting::get('social_tiktok'))
            <a href="{{ \App\Models\Setting::get('social_tiktok') }}" style="display:inline-block;width:32px;height:32px;background:rgba(245,240,232,0.08);border-radius:50%;margin:0 4px;text-align:center;line-height:36px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#8A8880" style="vertical-align:middle"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.53V6.77a4.85 4.85 0 01-1.02-.08z"/></svg>
            </a>
            @endif
        </div>

        <div class="footer-links">
            <a href="{{ url('/') }}" style="color:#B85C38;font-size:11px;letter-spacing:0.1em;text-transform:uppercase;margin:0 10px">Tienda</a>
            <a href="{{ route('product.search') }}?on_sale=1" style="color:#B85C38;font-size:11px;letter-spacing:0.1em;text-transform:uppercase;margin:0 10px">Ofertas</a>
            @if(\App\Models\Setting::get('store_whatsapp'))
            <a href="https://wa.me/{{ preg_replace('/\D/','',$wa=\App\Models\Setting::get('store_whatsapp')) }}" style="color:#B85C38;font-size:11px;letter-spacing:0.1em;text-transform:uppercase;margin:0 10px">WhatsApp</a>
            @endif
        </div>

        <p class="footer-legal">
            © {{ date('Y') }} EM Collective · Todos los derechos reservados<br>
            @if(isset($unsubscribeUrl))
            <a href="{{ $unsubscribeUrl }}" style="color:#4a4a48">Desuscribirse</a> ·
            @endif
            <a href="{{ url('/') }}" style="color:#4a4a48">emcollective.pe</a>
        </p>
    </div>

</td></tr>
</table>
</div>
</body>
</html>
