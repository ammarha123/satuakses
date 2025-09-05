<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Sertifikat – {{ $courseTitle }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #f7fcff;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
        }

        .border {
            position: absolute;
            left: 10mm;
            right: 10mm;
            top: 10mm;
            bottom: 10mm;
            border: 6px double #0ea5e9;
            border-radius: 6mm;
        }

        .container {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20mm 15mm;
        }

        .logo {
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 10mm;
            color: #0ea5e9;
            display: inline-block;
            border: 0.5mm solid #0ea5e9;
            padding: 2mm 5mm;
            border-radius: 3mm;
            margin-bottom: 4mm;
        }

        .title {
            font-size: 18mm;
            font-weight: 800;
            margin: 2mm 0 1mm;
            color: #111827;
        }

        .subtitle {
            color: #6b7280;
            font-size: 4mm;
            margin-bottom: 6mm;
        }

        .name {
            font-size: 14mm;
            font-weight: 700;
            margin: 2mm 0;
            color: #0f172a;
            text-transform: capitalize;
        }

        .course {
            font-size: 6mm;
            margin-top: 2mm;
            color: #111827;
            font-weight: 600;
        }

        .line {
            width: 120mm;
            height: 0.6mm;
            background: #94a3b8;
            margin: 6mm auto 3mm;
        }

        .footer {
            position: absolute;
            left: 15mm;
            right: 15mm;
            bottom: 12mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #334155;
            font-size: 3.5mm;
        }

        .badge {
            border: 0.4mm dashed #0ea5e9;
            padding: 2mm 4mm;
            border-radius: 2mm;
            color: #0ea5e9;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="border"></div>
        <div class="container">
            <div class="logo">SatuAkses</div>
            <div class="title">SERTIFIKAT</div>
            <div class="subtitle">Diberikan kepada</div>

            <div class="name">{{ $studentName }}</div>
            <div class="subtitle">atas keberhasilan menyelesaikan kursus</div>
            <div class="course">“{{ $courseTitle }}”</div>

            <div class="line"></div>
            <div class="subtitle">Tanggal terbit: {{ \Carbon\Carbon::parse($issuedAt)->translatedFormat('d F Y') }}
            </div>
        </div>

        <div class="footer mb-3">
            <div class="badge">ID: {{ $certId }}</div>
        </div>
    </div>
</body>

</html>
