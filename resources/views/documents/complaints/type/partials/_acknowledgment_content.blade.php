<p>Madame, Monsieur,</p>
<p>Nous vous remercions pour votre communication du {{ $complaint->complaint_date->format('d/m/Y') }}, recue par nos services le {{ $complaint->reception_date->format('d/m/Y') }} concernant votre dossier référencé <strong>{{ $complaint->complaint_reference }}</strong>.</p>
<p>Par la présente, nous vous confirmons que votre plainte a été officiellement enregistrée et transmise au département "{{ $complaint->department->name }}" pour une analyse approfondie.</p>
<p>Une réponse définitive vous sera communiquée dès que l'instruction de votre dossier sera terminée.</p>
<p>Nous vous prions d'agréer, Madame, Monsieur, l'expression de nos sentiments distingués.</p>
