<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Customer
 *
 * @property int $customer_id
 * @property int $walter_hope_smile_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|Profession[] $complaintsProfessions
 * @property Collection|Entity[] $complaintsEntities
 * @property Collection|Employee[] $complaintsEmployees
 * @property Collection|Complainant[] $complaintsComplainants
 * @property Collection|Complainant[] $representationsComplainants
 * @property Collection|Complaint[] $complaints
 * @property Collection|Representation[] $representations
 */
class Customer extends Model
{
    protected $table = 'customers';

    /**
     * @var string
     */
    protected $connection = 'mysql';

    protected $primaryKey = 'customer_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'walter_hope_smile_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'walter_hope_smile_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Complaint, $this>
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'customer_id', 'customer_id');
    }

    /**
     * @return HasMany<Representation, $this>
     */
    public function representations(): HasMany
    {
        return $this->hasMany(Representation::class, 'customer_id', 'customer_id');
    }
    /**
     * @return BelongsToMany<Profession, $this>
     */
    public function complaintsProfessions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'complaints', 'customer_id', 'profession_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'entity_id', 'employee_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Entity, $this>
     */
    public function complaintsEntities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'complaints', 'customer_id', 'entity_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'profession_id', 'employee_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Employee, $this>
     */
    public function complaintsEmployees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'complaints', 'customer_id', 'employee_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'profession_id', 'entity_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Complainant, $this>
     */
    public function complaintsComplainants(): BelongsToMany
    {
        return $this->belongsToMany(Complainant::class, 'complaints', 'customer_id', 'complainant_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'profession_id', 'entity_id', 'employee_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Complainant, $this>
     */
    public function representationsComplainants(): BelongsToMany
    {
        return $this->belongsToMany(Complainant::class, 'representations', 'customer_id', 'complainant_id')
            ->withTimestamps();
    }
}
