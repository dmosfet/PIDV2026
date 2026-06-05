<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accusé de réception de votre plainte</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf-style.css') }}">
</head>
<body>

<div class="left-border">
    <div class="logo-box">
        <img src="{{ public_path('img/logo-ifapme.jpg') }}" alt='logo' style="width: 150px; height: auto;">
    </div>
    @if ($complaint->department->department_type_id === 1)
        <div class="dept-title">{{ strtoupper($complaint->department->name) }}</div>
    @else
        <div class="dept-ifapme text-60-noir">Institut wallon<br> de Formation en Alternance et des indépendants<br> et des
            Petites et Moyennes Entreprises
        </div>
    @endif

    <div class="dept-info">
        <div>{{ $complaint->department->name }}</div>
        <div>{{ $complaint->department->address }}</div>
        <div>B-{{ $complaint->department->zip_code }} {{ $complaint->department->city }}</div>
    </div>

    <div class="sender">
        <strong>T: </strong>{{ $complaint->department->phone }}<br>
        {{ $complaint->department->email }}<br>
        www.ifapme.be
    </div>
</div>

<div class="main-wrapper">
    <div class="date-place">Fait à {{ $complaint->department->city }}, le {{ now()->format('d/m/Y') }}</div>

    <div class="recipient-box">
        <strong>À l'attention de :</strong><br>
        M./Mme {{ $complaint->complainant->name }}<br>
        {{ $complaint->complainant->address ?? 'Adresse non communiquée' }}
    </div>

    <div class="meta-data">
        <div class="reference">Référence: {{ $complaint->complaint_reference }}</div>
        Concerne : @yield('subject')
    </div>

    <main class="content">
        @yield('pdf_content')
    </main>

    <div class="signature-block">
        <p>{{ $complaint->employee->function ?? "Titre/fonction" }}</p>
        <div style="height: 60px;"></div>
        <p><strong>{{ $complaint->employee->name ?? 'Le Gestionnaire' }}</strong></p>
    </div>
</div>

</body>
</html>
