<?php

namespace App\Http\Controllers\Complaint;

use App\Enums\ComplainantType;
use App\Enums\ComplaintRecipientType;
use App\Enums\ComplaintStatus;
use App\Enums\ObjectCategory;
use App\Enums\Channel;
use App\Enums\ComplaintType;
use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Profession;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{

    /**
     * Permet de générer la liste des plaintes qui sont visibles pour l'utilisateur connecté.
     * Permet de récupérer les données nécessaires pour mettre en place un système de filtre des plaintes
     *
     * Attention d'un scope filtre sur le département de l'utilisateur. (cfr (App\Models\Scopes\DepartmentFilterScope)
     *
     * @return Factory|View
     */
    public function index()
    {
        // Récupérer des données pour les filtres
        $departments = Department::all();
        $complaintTypes = ComplaintType::cases();
        $complaintStatuses = ComplaintStatus::cases();

        // Récupération des compteurs par status
        $complaintsCounter = Complaint::groupBy('status')
            ->selectRaw('status, count(*) as total')
            ->pluck('total', 'status');

        // On génère l'array qui aide à l'affichage
        $statsByStatus = [];
        foreach (ComplaintStatus::cases() as $status) {
            $statsByStatus[] = [
                'label' => $status->label(),
                'count' => $complaintsCounter[$status->value] ?? 0,
                'class' => $status->cssClass(),
                'icon' => $status->icon(),
            ];
        }

        $complaints = Complaint::query()
            ->with(['complainant', 'department', 'employee'])
            ->select([
                'complaint_id',
                'complaint_type_id',
                'complaint_reference',
                'complainant_id',
                'department_id',
                'employee_id',
                'status',
                'complaint_date',
                'created_by_user',
                'created_by_department',
                'appealed_by_id',
                'appeal_about_id',
            ])

            // Filtres
            ->when(request('type'), fn($q, $type) => $q->where('complaint_type_id', $type)
            )
            ->when(request('status'), fn($q, $status) => $q->where('status', $status)
            )
            ->when(request('department'), fn($q, $dept) => $q->where('department_id', $dept)
            )
            ->latest('complaint_date')
            ->paginate(15)->withQueryString();

        return view('complaints.index', compact('complaints', 'statsByStatus', 'departments', 'complaintTypes', 'complaintStatuses'));
    }

    /**
     * Permet de récupérer les données nécessaires pour créer une plainte.
     *
     * @return Factory|View
     */
    public function create()
    {
        // On récupère les données des menus déroulants
        $objectCategories = ObjectCategory::cases();
        $channels = Channel::cases();
        $professions = Profession::all();
        $complaintTypes =  ComplaintType::cases();
        $departments = Department::all();

        // On récupère les plaignants et clients déjà encodés
        $complainants = Complaint::all();
        $customers = Customer::all();

        return view('complaints.create', compact('objectCategories', 'channels', 'departments','complaintTypes','complainants', 'customers', 'professions'));
    }

    /**
     * Permet de sauvegarder la nouvelle plainte
     *
     * @param Request $request Données validées du formulaire de création d'une plainte
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // On lance une transaction vu qu'on va possiblement modifier plusieurs tables
            DB::beginTransaction();

            // 1. Normalisation stricte
            $lastName = trim(mb_strtoupper($request->complainant_lastname));
            $firstName = trim(mb_convert_case($request->complainant_firstname, MB_CASE_TITLE, "UTF-8"));
            $email = trim(mb_strtolower($request->complainant_email));
            $dob = $request->filled('complainant_dateofbirth')
                ? \Carbon\Carbon::parse($request->complainant_dateofbirth)->format('Y-m-d')
                : null;

            // 2. Recherche multicritère
            $complainant = Complainant::where('last_name', $lastName)
                ->where('first_name', $firstName)
                ->where(function ($query) use ($dob, $email) {
                    // On considère que c'est la même personne si :
                    // La date de naissance correspond OU l'email correspond
                    // OU si la date en base est vide (on va alors la remplir)
                    $query->where('date_of_birth', $dob)
                        ->orWhere('email', $email)
                        ->orWhereNull('date_of_birth');
                })
                ->first();

            // 3. Update ou Create
            if ($complainant) {
                $complainant->update([
                    'email' => $email,
                    'phone' => $request->complainant_phone,
                    'address' => $request->complainant_address,
                    'zip_code' => $request->complainant_zip_code,
                    'city' => $request->complainant_city,
                    'country' => $request->complainant_country,
                    'date_of_birth' => $complainant->date_of_birth ?? $dob,
                ]);
            } else {
                $complainant = Complainant::create([
                    'last_name' => $lastName,
                    'first_name' => $firstName,
                    'email' => $email,
                    'date_of_birth' => $dob,
                    'phone' => $request->complainant_phone,
                    'address' => $request->complainant_address,
                    'zip_code' => $request->complainant_zip_code,
                    'city' => $request->complainant_city,
                    'country' => $request->complainant_country,
                ]);
            }

            // Sur base de l'ID smarter encodé dans le champ Customer ID du formulaire, on recherche s'il est déjà présent dans
            // la table customers. S'il n'existe pas, on le créé. Dans tous les cas, on renvoie le customer_id.
            $customerId = null;

            if ($request->customer_id):
                // On cherche dans la table de liaison
                $link = Customer::firstOrCreate(['walter_hope_smile_id' => $request->customer_id]);
                $customerId = $link->customer_id;
            endif;

            // Créer la plainte
            $complaint = Complaint::create([
                // Informations générales
                'complaint_date' => $request->complaint_date,
                'reception_date' => $request->reception_date,
                'complaint_type_id' => $request->complaint_type_id,
                'channel_id' => $request->channel_id,
                'object_category_id' => $request->object_category_id,

                // Parties concernées
                'complainant_id' => $complainant->complainant_id,
                'customer_id' => $customerId,
                'profession_id' => $request->profession_id,

                // Object de la plainte
                'object' => $request->object,

            ]);

            DB::commit();

            return redirect()
                ->route('complaints.show', $complaint->complaint_id)
                ->with('success', 'La plainte a été créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de la plainte: ' . $e->getMessage()]);
        }
    }

    /**
     * Permet de générer les données pour visualiser les détails d'une plainte.
     *
     * @param Complaint $complaint La plainte à visualiser.
     * @return Factory|View
     */
    public function show(Complaint $complaint)
    {
        // On load les relations manquantes
        $complaint->load([
            'appealed_by',
            'appeal_about',
            'customer',
            'employee',
            'department',
            'profession',
        ]);

        return view('complaints.show_tab', compact('complaint'));
    }

    /**
     * Permet de générer les données nécessaires à la modification d'une plainte.
     *
     * @param Complaint $complaint
     * @return Factory|View
     */
    public function edit(Complaint $complaint)
    {
        // On récupère les données des menus déroulants
        $object_categories = ObjectCategory::cases();
        $channels = Channel::cases();
        $professions = Profession::all();
        $complaintTypes = ComplaintType::cases();
        $departments = Department::all();


        // On filtre les types de plaintes possibles sur base de l'utilisateur connecté.
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        $deptId = $user->employee?->department_id;

        return view('complaints.edit', compact('complaint', 'object_categories', 'departments','channels', 'complaintTypes',  'professions'));
    }

    /**
     * Permet de mettre à jour la plainte sur base du formulaire reçu.
     *
     * @param Request $request Les données du formulaire de mise à jour
     * @param Complaint $complaint La plainte à mettre à jour
     * @return void
     */
    public function update(Request $request, Complaint $complaint): RedirectResponse
    {
        try {

            DB::beginTransaction();

            // 1. Normalisation stricte (identique au store)
            $lastName = trim(mb_strtoupper($request->complainant_lastname));
            $firstName = trim(mb_convert_case($request->complainant_firstname, MB_CASE_TITLE, "UTF-8"));
            $email = trim(mb_strtolower($request->complainant_email));
            $dob = $request->filled('complainant_dateofbirth')
                ? \Carbon\Carbon::parse($request->complainant_dateofbirth)->format('Y-m-d')
                : null;

            // 2. Mise à jour du plaignant lié à cette plainte
            $complaint->complainant->update([
                'last_name' => $lastName,
                'first_name' => $firstName,
                'email' => $email,
                'phone' => $request->complainant_phone,
                'address' => $request->complainant_address,
                'zip_code' => $request->complainant_zip_code,
                'city' => $request->complainant_city,
                'country' => $request->complainant_country,
                'date_of_birth' => $dob ?? $complaint->complainant->date_of_birth,
            ]);

            // 3. Gestion du customer (ID Smarter)
            $customerId = null;

            if ($request->filled('customer_id')) {
                $link = Customer::firstOrCreate(['walter_hope_smile_id' => $request->customer_id]);
                $customerId = $link->customer_id;
            }

            // 4. Mise à jour de la plainte elle-même
            $complaint->update([
                // Informations générales
                'complaint_date' => $request->complaint_date,
                'reception_date' => $request->reception_date,
                'complaint_type_id' => $request->complaint_type_id,
                'channel_id' => $request->channel_id,
                'object_category_id' => $request->object_category_id,

                // Parties concernées
                'customer_id' => $customerId,
                'profession_id' => $request->profession_id,

                // Objet
                'object' => $request->object,
            ]);

            DB::commit();

            return redirect()
                ->route('complaints.show', $complaint->complaint_id)
                ->with('success', 'La plainte a été modifiée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la modification de la plainte : ' . $e->getMessage()]);
        }
    }

    /**
     * Permet de supprimer une plainte
     *
     * @param Complaint $complaint La plainte à supprimer
     * @return void
     */
    public function destroy(Complaint $complaint)
    {
        //
    }
}
