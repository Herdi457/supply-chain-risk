<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Risiko_Logistik_{{ $risk->country->code }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @media print {
            body { background: white; color: black; }
            .no-print { display: none; }
            .print-card { border: 1px solid #cbd5e1; page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans p-8">

    <div class="no-print max-w-3xl mx-auto mb-6 flex justify-between items-center bg-slate-900 text-white p-4 rounded-xl shadow-md border border-slate-800">
        <div class="text-xs font-medium text-slate-300">
            Sistem Dokumen Manajemen Risiko Rantai Pasok Global.
        </div>
        <div class="flex gap-2">
            <a href="/" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg text-xs font-bold transition-colors text-white no-underline">
                ← Kembali ke Peta
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg transition-all cursor-pointer">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg border border-slate-200 shadow-sm print-card">
        
        <div class="text-center border-b-4 border-double border-slate-800 pb-4 mb-6">
            <h1 class="text-xl font-black tracking-wide uppercase text-slate-900">GLOBAL SUPPLY CHAIN RISK ASSESSMENT REPORT</h1>
            <p class="text-xs text-slate-600 mt-1 uppercase tracking-wider">INTEGRATED LOGISTICS SECURITY ENGINE CONTROL PANEL</p>
            <p class="text-[10px] text-slate-400 font-mono mt-1">Generated System Date: {{ now()->format('d M Y - H:i:s') }} WIB</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Negara Objek Analisis</span>
                <span class="text-lg font-extrabold text-slate-800">{{ $risk->country->name }} ({{ $risk->country->code }})</span>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Kesimpulan Level Risiko</span>
                <span class="inline-block px-3 py-1 mt-1 text-xs font-black uppercase rounded border
                    {{ $risk->risk_level == 'High Risk' ? 'bg-red-50 border-red-200 text-red-700' : ($risk->risk_level == 'Medium Risk' ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-emerald-50 border-emerald-200 text-emerald-700') }}">
                    {{ $risk->risk_level }}
                </span>
            </div>
        </div>

        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">📍 Rincian Komponen Penilaian Risiko (Weighted Model)</h3>
        <table class="w-full text-left text-xs border-collapse border border-slate-200 rounded-lg overflow-hidden mb-6">
            <thead>
                <tr class="bg-slate-100 text-slate-700 border-b border-slate-200 font-bold">
                    <th class="p-3 w-12 text-center">No</th>
                    <th class="p-3">Indikator Parameter Risiko</th>
                    <th class="p-3 text-center w-32">Skor Risiko Parsial</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <tr>
                    <td class="p-3 text-center font-mono">1</td>
                    <td class="p-3">Ancaman Cuaca & Badai Ekstrem Laut (OpenWeather API)</td>
                    <td class="p-3 text-center font-bold text-slate-800">{{ round($risk->weather_risk_score, 1) }}%</td>
                </tr>
                <tr>
                    <td class="p-3 text-center font-mono">2</td>
                    <td class="p-3">Makroekonomi & Tingkat Inflasi Negara (ExchangeRate API)</td>
                    <td class="p-3 text-center font-bold text-slate-800">{{ round($risk->inflation_risk_score, 1) }}%</td>
                </tr>
                <tr>
                    <td class="p-3 text-center font-mono">3</td>
                    <td class="p-3">Volatilitas Kerugian Nilai Tukar Mata Uang</td>
                    <td class="p-3 text-center font-bold text-slate-800">{{ round($risk->exchange_rate_risk_score, 1) }}%</td>
                </tr>
                <tr>
                    <td class="p-3 text-center font-mono">4</td>
                    <td class="p-3">Geopolitik Berita Global (Lexicon Sentiment Engine AI)</td>
                    <td class="p-3 text-center font-bold text-slate-800">{{ round($risk->news_sentiment_risk_score, 1) }}%</td>
                </tr>
                <tr class="bg-blue-50/50 font-bold text-blue-900 text-sm">
                    <td colspan="2" class="p-3 text-right">TOTAL SKOR AKHIR TERTIMBANG (WEIGHTED TOTAL SCORE) :</td>
                    <td class="p-3 text-center text-base font-black text-blue-600">{{ round($risk->total_risk_score, 1) }}%</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-16 pt-8 border-t border-slate-100 grid grid-cols-2 text-xs">
            <div>
                <p class="font-bold text-slate-800">Otoritas Penguji:</p>
                <p class="text-slate-400 text-[11px] font-mono mt-1">Dokumen ID: SR-{{ mt_rand(1000,9999) }}-{{ $risk->country->code }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Status: Terverifikasi Sistem Elektronik Terpusat</p>
            </div>
            <div class="text-right flex flex-col items-end">
                <p class="text-slate-500">Petugas Penilai Analyst,</p>
                <div class="w-24 h-12 my-2 border border-dashed border-slate-200 bg-slate-50/50 flex items-center justify-center rounded text-[10px] text-slate-400 font-mono italic">
                    E-Signature
                </div>
                <p class="font-bold text-slate-800">Analis Logistik Utama</p>
            </div>
        </div>

    </div>

</body>
</html>