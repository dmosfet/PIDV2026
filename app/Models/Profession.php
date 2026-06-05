<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Profession
 *
 * @property int $profession_id
 * @property string $code
 * @property string $name
 * @property int $pathway_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Pathway $pathway
 * @property Collection|Category[] $complaintsCategories
 * @property Collection|Channel[] $complaintsChannels
 * @property Collection|Entity[] $complaintsEntities
 * @property Collection|Customer[] $complaintsCustomers
 * @property Collection|Employee[] $complaintsEmployees
 * @property Collection|Complainant[] $complaintsComplainants
 * @property Collection|Complaint[] $complaints
 */
class Profession extends Model
{
    protected $table = 'professions';

    /**
     * @var string
     */
    protected $connection = 'mysql';

    protected $primaryKey = 'profession_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'profession_id',
        'code',
        'name',
        'pathway_id',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'created_at' => 'CURRENT_TIMESTAMP',
        'updated_at' => 'CURRENT_TIMESTAMP',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'profession_id' => 'integer',
            'code' => 'string',
            'name' => 'string',
            'pathway_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Complaint, $this>
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'profession_id', 'profession_id');
    }

    /**
     * @return BelongsTo<Pathway, $this>
     */
    public function pathway(): BelongsTo
    {
        return $this->belongsTo(Pathway::class, 'pathway_id', 'pathway_id');
    }

    /**
     * @return BelongsToMany<Category, $this>
     */
    public function complaintsCategories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'complaints', 'profession_id', 'category_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'channel_id', 'entity_id', 'customer_id', 'employee_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Channel, $this>
     */
    public function complaintsChannels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'complaints', 'profession_id', 'channel_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'entity_id', 'customer_id', 'employee_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Entity, $this>
     */
    public function complaintsEntities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'complaints', 'profession_id', 'entity_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'customer_id', 'employee_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Customer, $this>
     */
    public function complaintsCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'complaints', 'profession_id', 'customer_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'entity_id', 'employee_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Employee, $this>
     */
    public function complaintsEmployees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'complaints', 'profession_id', 'employee_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'entity_id', 'customer_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Complainant, $this>
     */
    public function complaintsComplainants(): BelongsToMany
    {
        return $this->belongsToMany(Complainant::class, 'complaints', 'profession_id', 'complainant_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'entity_id', 'customer_id', 'employee_id')
            ->withTimestamps();
    }
}
