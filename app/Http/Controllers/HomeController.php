<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintLog;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Plaintes récentes pour affichage
        $recentComplaints = Complaint::query()
            // 1. Sélectionner uniquement les colonnes nécessaires de la table principale
            ->select([
                'complaint_id',
                'complaint_reference',
                'complaint_date',
                'status',
                'complainant_id'
            ])
            // 2. Filtrer par date (15 derniers jours)
            ->where('complaint_date', '>=', now()->subDays(15))

            // 3. Charger les relations avec uniquement les colonnes utilies
            ->with([
                'complainant' => function ($query) {
                    $query->select('complainant_id', 'last_name', 'first_name');
                }
            ])

            // 4. Trier par date décroissante
            ->latest('complaint_date')

            // 5. Récupérer les données (limité à 5) affichage uniquement des 4 dernières.
            ->take(5)
            ->get();

        // Récupération des informations de notifications qui concerne le département de l'utilisateur
        $user = auth()->user();

        $departmentId = $user->employee?->department_id;

        $notifications = [];

        return view('home.index', compact('recentComplaints', 'notifications'));

    }
}
