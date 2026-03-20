<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('subject', 'EM Collective')</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4efe6; color: #1A1A18; -webkit-text-size-adjust: 100%; }
    .email-wrapper { max-width: 600px; margin: 32px auto; background: #FFFFFF; }
    .email-header { background: #1A1A18; padding: 28px 40px; text-align: center; }
    .email-header h1 { font-family: Georgia, 'Times New Roman', serif; color: #F5F0E8; font-size: 24px; font-weight: 300; letter-spacing: 0.2em; }
    .email-header p { color: #8A8880; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; margin-top: 4px; }
    .accent-bar { height: 3px; background: #C4714A; }
    .email-body { padding: 40px; }
    .email-footer { background: #1A1A18; padding: 28px 40px; text-align: center; }
    .email-footer p { color: #8A8880; font-size: 11px; line-height: 1.6; }
    .email-footer a { color: #C4714A; text-decoration: none; }
    h2 { font-family: Georgia, 'Times New Roman', serif; font-size: 28px; font-weight: 300; margin-bottom: 16px; }
    p { font-size: 14px; line-height: 1.7; color: #4a4a48; margin-bottom: 12px; }
    .btn { display: inline-block; background: #1A1A18; color: #F5F0E8 !important; padding: 14px 32px; text-decoration: none; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; margin: 8px 0; }
    .btn:hover { background: #C4714A; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px 12px; text-align: left; font-size: 13px; border-bottom: 1px solid #e8e3da; }
    th { background: #f4efe6; font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: #8A8880; }
    .divider { height: 1px; background: #e8e3da; margin: 24px 0; }
    .highlight { background: #f4efe6; padding: 16px 20px; border-left: 3px solid #C4714A; margin: 16px 0; }
    @media (max-width: 600px) {
        .email-body { padding: 24px 20px; }
        .email-header, .email-footer { padding: 20px; }
    }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td style="padding: 0;">
<div class="email-wrapper">
    <div class="email-header">
        <h1>EM COLLECTIVE</h1>
        <p>Moda editorial para toda la familia</p>
    </div>
    <div class="accent-bar"></div>
    <div class="email-body">
        @yield('body')
    </div>
    <div class="accent-bar"></div>
    <div class="email-footer">
        <p>
            © {{ date('Y') }} EM Collective. Todos los derechos reservados.<br>
            <a href="{{ url('/') }}">emcollective.pe</a>
            @if(isset($unsubscribeUrl))
            · <a href="{{ $unsubscribeUrl }}">Desuscribirse</a>
            @endif
        </p>
        <p style="margin-top: 8px;">
            <a href="{{ url('/') }}" style="color: #8A8880; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; text-decoration: none;">
                Instagram &nbsp;·&nbsp; Facebook &nbsp;·&nbsp; TikTok
            </a>
        </p>
    </div>
</div>
</td></tr>
</table>
</body>
</html>
