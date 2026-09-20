<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Minha Saúde EI' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{
            --green-700:#0f6b4f;
            --green-600:#15805e;
            --green-500:#1a9a71;
            --green-50:#eefaf4;
            --ink:#1f2937;
            --muted:#6b7280;
            --border:#e2e8e5;
            --danger:#dc2626;
        }
        .auth-body{
            margin:0;
            min-height:100vh;
            font-family:"Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            background:
                radial-gradient(1200px 500px at 50% -10%, var(--green-50), transparent),
                #f7f8f7;
            color:var(--ink);
            display:flex;
            align-items:center;
            justify-content:center;
            padding:32px 16px;
        }
        .auth-page{ width:100%; max-width:460px; }
        .brand{ text-align:center; margin-bottom:28px; }
        .brand-mark{
            width:52px;height:52px;
            margin:0 auto 12px;
            border-radius:14px;
            background:linear-gradient(155deg, var(--green-500), var(--green-700));
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 8px 20px -6px rgba(15,107,79,.45);
        }
        .brand-mark svg{width:26px;height:26px;}
        .brand h1{ font-size:19px; font-weight:700; margin:0 0 2px; color:#134e3a; }
        .brand p{ margin:0; font-size:13.5px; color:var(--muted); }
        .card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:18px;
            padding:32px 30px 28px;
            box-shadow:0 20px 40px -20px rgba(16,24,20,0.18), 0 2px 6px rgba(16,24,20,0.04);
        }
        .card h2{ font-size:20px; margin:0 0 4px; font-weight:700; }
        .card > p.sub{ margin:0 0 24px; font-size:13.5px; color:var(--muted); }
        .field{ margin-bottom:18px; }
        .field label{ display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
        .input-wrap{ position:relative; }
        .input-wrap svg{
            position:absolute; left:13px; top:50%; transform:translateY(-50%);
            width:17px;height:17px; color:#9aa5a1; pointer-events:none;
        }
        .field input{
            width:100%;
            padding:11px 14px 11px 40px;
            font-size:14.5px;
            border:1.5px solid var(--border);
            border-radius:10px;
            background:#fbfcfb;
            color:var(--ink);
            outline:none;
            transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
            font-family:inherit;
        }
        .field input::placeholder{ color:#aab3ae; }
        .field input:hover{ border-color:#c9d3ce; }
        .field input:focus{
            border-color:var(--green-500);
            background:#fff;
            box-shadow:0 0 0 3.5px rgba(26,154,113,.15);
        }
        .field.has-error input{ border-color:var(--danger); }
        .error-text{ font-size:12.5px; color:var(--danger); margin-top:5px; }
        .toggle-pass{
            position:absolute; right:10px; top:50%; transform:translateY(-50%);
            background:none; border:none; cursor:pointer; color:#9aa5a1; padding:4px; display:flex;
        }
        .toggle-pass:hover{ color:var(--green-600); }
        .hint{ font-size:12px; color:var(--muted); margin-top:6px; }
        button.submit{
            width:100%;
            padding:12.5px 16px;
            margin-top:6px;
            background:linear-gradient(155deg, var(--green-500), var(--green-700));
            color:#fff;
            border:none;
            border-radius:10px;
            font-size:15px;
            font-weight:700;
            font-family:inherit;
            cursor:pointer;
            box-shadow:0 10px 20px -8px rgba(15,107,79,.5);
            transition:filter .15s ease, transform .05s ease;
        }
        button.submit:hover{ filter:brightness(1.06); }
        button.submit:active{ transform:translateY(1px); }
        .foot{ text-align:center; margin-top:22px; font-size:13.5px; color:var(--muted); }
        .foot a{ color:var(--green-700); font-weight:700; text-decoration:none; }
        .foot a:hover{ text-decoration:underline; }
    </style>
</head>
<body class="auth-body">
    <div class="auth-page">
        <div class="brand">
            <div class="brand-mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 21s-7-4.6-9.5-9.1C.7 8.1 2.4 4.5 6 4c2-.3 3.6.7 4.9 2.3C12.2 4.7 13.8 3.7 15.8 4c3.6.5 5.3 4.1 3.5 7.9C16.8 16.4 12 21 12 21z"/>
                    <path d="M9 12h2l1-2 1 4 1-2h1"/>
                </svg>
            </div>
            <h1>Posto de Saúde Minha Saúde EI</h1>
            <p>Agendamento e Gestão de Filas</p>
        </div>

        {{ $slot }}
    </div>

    <script>
        document.querySelectorAll('.toggle-pass').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById(btn.dataset.toggle);
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    </script>
</body>
</html>
