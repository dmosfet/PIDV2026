<?php

namespace App\Http\Controllers\Complaint;

use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ComplaintDocumentController extends Controller
{

    /**
     * @param Request $request
     * @param Complaint $complaint
     * @param DocumentType $type
     * @return Pdf|\Illuminate\Http\Response
     */
    public function generate(Request $request, Complaint $complaint, DocumentType $type)
    {
        $data = $type->prepareData($request->all());

        // On stocke l'instance du PDF
        $pdf = Pdf::loadView($type->bladeView(), [
            'complaint' => $complaint,
            'data' => $data,
            'date' => now()->format('d/m/Y'),
        ]);

        // On retourne la réponse appropriée
        return $pdf->stream("document-{$complaint->complaint_id}.pdf");
    }

}
