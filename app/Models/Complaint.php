<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ComplaintRecipientType;
use App\Enums\ObjectCategory;
use App\Enums\Channel;
use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use App\Models\Scopes\DepartmentFilterScope;
use App\Models\Traits\HasComplaintAttributes;
use App\Observers\ComplaintObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Complaint
 *
 * @property int $complaint_id
 * @property string $complaint_reference
 * @property ComplaintStatus|null $status
 * @property ComplaintType|null $complaint_type_id
 * @property Carbon|null $complaint_date
 * @property Carbon|null $reception_date
 * @property string|null $object
 * @property Carbon|null $evaluation_date
 * @property bool|null $admissible
 * @property bool|null $well_founded
 * @property Carbon|null $acknowledgment_date
 * @property Carbon|null $transmission_date
 * @property string|null $response
 * @property Carbon|null $response_date
 * @property int|null $duration
 * @property ObjectCategory $object_category_id
 * @property Channel $channel_id
 * @property int|null $profession_id
 * @property int|null $department_id
 * @property int|null $customer_id
 * @property int|null $employee_id
 * @property int|null $complainant_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $created_by_user
 * @property int $created_by_department
 *
 * // Attributes
 * @property-read string $min_date
 * @property-read int $deadline_days
 * @property-read string $alert_level
 *
 * // Relations
 * @property-read Profession|null $profession
 * @property-read Department|null $department
 * @property-read Customer|null $customer
 * @property-read Employee|null $employee
 * @property-read Complainant|null $complainant
 */
class Complaint extends Model
{
    // On inclut des attributs supplémentaires au modèle
    use HasComplaintAttributes;
    use HasFactory;

    protected $table = 'complaints';
    protected $connection = 'mysql';
    protected $primaryKey = 'complaint_id';
    public $timestamps = true;

    // Attribut optionnel utilisé pour la création de log
    public ?string $logComment = null;

    protected $fillable = [
        'complaint_reference',
        'complaint_type_id',
        'complaint_recipient_type_id',
        'status',
        'complaint_date',
        'reception_date',
        'object',
        'evaluation_date',
        'admissible',
        'well_founded',
        'acknowledgment_date',
        'transmission_date',
        'response',
        'response_date',
        'duration',
        'object_category_id',
        'channel_id',
        'profession_id',
        'department_id',
        'customer_id',
        'employee_id',
        'complainant_id',
        'appealed_by_id',
        'appeal_about_id',
        'target_reference',
        'created_by_user',
        'created_by_department',
    ];

    protected function casts(): array
    {
        return [
            // Enums pour remplacer des données statiques
            'channel_id' => Channel::class,
            'object_category_id' => ObjectCategory::class,
            'complaint_type_id' => ComplaintType::class,
            'complaint_recipient_type_id' => ComplaintRecipientType::class,
            'status' => ComplaintStatus::class,

            // Reste des champs de la DB
            'complaint_reference' => 'string',
            'complaint_id' => 'integer',
            'complaint_date' => 'date',
            'reception_date' => 'date',
            'object' => 'string',
            'evaluation_date' => 'datetime',
            'admissible' => 'boolean',
            'well_founded' => 'boolean',
            'acknowledgment_date' => 'datetime',
            'transmission_date' => 'datetime',
            'response' => 'string',
            'response_date' => 'datetime',
            'duration' => 'integer',
            'profession_id' => 'integer',
            'department_id' => 'integer',
            'customer_id' => 'integer',
            'employee_id' => 'integer',
            'complainant_id' => 'integer',

            // Champs liés aux recours
            // ID du recours qui concerne cette plainte
            'appealed_by_id' => 'integer',
            // La référence de la plainte concernée par ce recours
            'appeal_about_id' => 'integer',

            // Champs de log
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'created_by_user' => 'integer',
            'created_by_department' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Profession, $this>
     */
    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'profession_id', 'profession_id');
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    /**
     * @return BelongsTo<Employee, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * @return BelongsTo<Complainant, $this>
     */
    public function complainant(): BelongsTo
    {
        return $this->belongsTo(Complainant::class, 'complainant_id', 'complainant_id');
    }

    public function complaint_logs(): HasMany
    {
        return $this->hasMany(ComplaintLog::class, 'complaint_id', 'complaint_id');
    }

    public function appeal_about(): BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'appeal_about_id', 'complaint_id')
            ->withoutGlobalScopes();
    }

    public function appealed_by(): BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'appealed_by_id', 'complaint_id')
            ->withoutGlobalScopes();
    }



    public function isRecours(): bool
    {
        return $this->complaint_type_id === ComplaintType::RECOURS->value;
    }

    /**
     * Fonction qui retourne la mise en forme de l'icone représentant chaque étape du workflow.
     * Bleu= prochaine étape
     * Vert= étape réalisée
     * Rouge= plainte a été rejetée
     * @param ComplaintStatus $targetStep
     * @return string
     */
    public function getStepColor(ComplaintStatus $targetStep): string
    {

        if ($this->status === ComplaintStatus::REJECTED) {
            return 'text-secondary opacity-25';
        }

        $statuses = ComplaintStatus::cases();
        $currentIndex = array_search($this->status, $statuses);
        $targetIndex  = array_search($targetStep, $statuses);

        // Cas spécifique : Tout est fini
        if ($this->status === ComplaintStatus::CLOSED) {
            return 'text-success';
        }

        // LOGIQUE DE DÉCALAGE :
        // L'étape actuelle (bleue) est l'étape immédiatement APRÈS le statut actuel.
        // L'étape du statut actuel est déjà terminée (verte).

        return match (true) {
            // L'étape cible est celle juste après mon statut actuel -> BLEU (À faire)
            $targetIndex === $currentIndex + 1 => 'text-primary',

            // L'étape cible est mon statut actuel ou avant -> VERT (Fait)
            $targetIndex <= $currentIndex     => 'text-success',

            // Le reste est dans le futur lointain -> GRIS
            default                           => 'text-secondary opacity-25',
        };
    }

    public function getStepLabel(ComplaintStatus $targetStep): string
    {
        $statuses = ComplaintStatus::cases();
        $currentIndex = array_search($this->status, $statuses);
        $targetIndex  = array_search($targetStep, $statuses);

        // Si on est à l'étape ou déjà passé : on affiche l'état réalisé
        if ($targetIndex <= $currentIndex) {
            return $targetStep->label();
        }

        // Sinon, on affiche l'action à faire
        return $targetStep->todoLabel();
    }

    /**
     * Retourne les plaintes qui sont éligibles pour un recours. Le status répondu garanti que la plainte a été traitée récemment.
     * @return mixed
     */
    public static function getEligibleComplaintsForAppeals()
    {
        return self::withoutGlobalScopes()
            ->with(['complainant:complainant_id,first_name,last_name'])
            ->whereIn('status', [ComplaintStatus::RESPONDED, ComplaintStatus::REJECTED])
            ->whereNull('appealed_by_id')
            ->select('complaint_id', 'complaint_reference', 'status','complaint_date', 'complainant_id')
            ->orderBy('complaint_date', 'desc')
            ->get();
    }

    /**
     * On active un scope qui empêche l'utilisateur connecté d'accéder à des plaintes qui ne sont pas de son département
     * On active un observer qui va logger les modifications
     *
     * @return void
     */
    protected static function booted(): void
    {
        // Défini un scope qui limite chaque user à son département (sauf admin)
        static::addGlobalScope(new DepartmentFilterScope);

        // Observer qui log les modifications de champs liés au workflow
        static::observe(ComplaintObserver::class);
    }
}
