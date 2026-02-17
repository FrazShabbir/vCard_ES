<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Data Import · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep: #0a0a0f;
            --bg-card: rgba(18, 18, 24, 0.85);
            --bg-card-border: rgba(255, 255, 255, 0.06);
            --accent: #00d4aa;
            --accent-dim: rgba(0, 212, 170, 0.15);
            --accent-glow: rgba(0, 212, 170, 0.4);
            --text: #f0f0f5;
            --text-muted: #8b8b9e;
            --danger: #ff6b6b;
            --success: #00d4aa;
            --radius: 20px;
            --radius-sm: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', system-ui, sans-serif;
            background: var(--bg-deep);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Mesh / gradient background */
        .bg-wrap {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(0, 212, 170, 0.12), transparent),
                radial-gradient(ellipse 60% 40% at 100% 50%, rgba(88, 28, 135, 0.08), transparent),
                radial-gradient(ellipse 50% 30% at 0% 80%, rgba(0, 120, 212, 0.06), transparent);
        }

        .grid-overlay {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 64px 64px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black, transparent);
        }

        main {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .card {
            width: 100%;
            max-width: 560px;
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            border-radius: var(--radius);
            padding: 2.5rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .icon-wrap {
            width: 72px;
            height: 72px;
            margin: 0 auto 1.25rem;
            background: var(--accent-dim);
            border: 1px solid rgba(0, 212, 170, 0.25);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrap svg {
            width: 36px;
            height: 36px;
            stroke: var(--accent);
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .targets {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 1.25rem;
        }

        .pill {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 999px;
            color: var(--text-muted);
        }

        .btn-run {
            width: 100%;
            padding: 1rem 1.5rem;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            color: var(--bg-deep);
            background: var(--accent);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-run:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 0 30px var(--accent-glow);
        }

        .btn-run:disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }

        .btn-run .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .result {
            margin-top: 1.5rem;
            padding: 1.25rem;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            display: none;
        }

        .result.visible {
            display: block;
        }

        .result.success {
            background: rgba(0, 212, 170, 0.1);
            border: 1px solid rgba(0, 212, 170, 0.25);
            color: var(--success);
        }

        .result.error {
            background: rgba(255, 107, 107, 0.1);
            border: 1px solid rgba(255, 107, 107, 0.25);
            color: var(--danger);
        }

        .result-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .result-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .stat {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
            background: rgba(0,0,0,0.2);
            border-radius: 8px;
            text-align: center;
        }

        .stat span {
            color: var(--text-muted);
            font-weight: 400;
        }

        .footer-note {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .footer-note a {
            color: var(--accent);
            text-decoration: none;
        }

        .footer-note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="bg-wrap"></div>
    <div class="grid-overlay"></div>

    <main>
        <div class="card">
            <div class="card-header">
                <div class="icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <h1>World Data Import</h1>
                <p class="subtitle">Seed regions, subregions, and geographic data for Pakistan, United Kingdom, India, and United States.</p>
                <div class="targets">
                    <span class="pill">Pakistan</span>
                    <span class="pill">United Kingdom</span>
                    <span class="pill">India</span>
                    <span class="pill">United States</span>
                </div>
            </div>

            <button type="button" class="btn-run" id="btnRun" aria-busy="false">
                <span class="btn-text">Run import</span>
            </button>

            <div class="result" id="result" role="status" aria-live="polite">
                <div class="result-title" id="resultTitle"></div>
                <div id="resultMessage"></div>
                <div class="result-stats" id="resultStats"></div>
            </div>
        </div>
    </main>

    <p class="footer-note">
        <a href="{{ route('home') }}">{{ config('app.name') }}</a> · World Data Import
    </p>

    <script>
        (function() {
            const btn = document.getElementById('btnRun');
            const result = document.getElementById('result');
            const resultTitle = document.getElementById('resultTitle');
            const resultMessage = document.getElementById('resultMessage');
            const resultStats = document.getElementById('resultStats');

            btn.addEventListener('click', async function() {
                btn.disabled = true;
                btn.setAttribute('aria-busy', 'true');
                const textSpan = btn.querySelector('.btn-text');
                const origText = textSpan.textContent;
                textSpan.outerHTML = '<span class="spinner"></span><span class="btn-text">Importing…</span>';

                result.classList.remove('visible', 'success', 'error');
                resultStats.innerHTML = '';

                try {
                    const res = await fetch('{{ route("import.world.data.run") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({})
                    });

                    const data = await res.json();

                    if (data.success) {
                        result.classList.add('visible', 'success');
                        resultTitle.textContent = 'Import completed';
                        resultMessage.textContent = data.message || 'Data seeded successfully.';

                        if (data.stats) {
                            const labels = { regions: 'Regions', subregions: 'Subregions', countries: 'Countries', states: 'States', cities: 'Cities' };
                            for (const [key, val] of Object.entries(data.stats)) {
                                const div = document.createElement('div');
                                div.className = 'stat';
                                div.innerHTML = '<strong>' + (val || 0) + '</strong> <span>' + (labels[key] || key) + '</span>';
                                resultStats.appendChild(div);
                            }
                        }
                    } else {
                        result.classList.add('visible', 'error');
                        resultTitle.textContent = 'Import failed';
                        resultMessage.textContent = data.message || 'An error occurred.';
                    }
                } catch (e) {
                    result.classList.add('visible', 'error');
                    resultTitle.textContent = 'Error';
                    resultMessage.textContent = e.message || 'Network or server error.';
                }

                btn.disabled = false;
                btn.setAttribute('aria-busy', 'false');
                btn.innerHTML = '<span class="btn-text">' + origText + '</span>';
            });
        })();
    </script>
</body>
</html>
