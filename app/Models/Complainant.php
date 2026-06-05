<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Channel;
use App\Enums\ComplainantType;
use App\Enums\ObjectCategory;
use App\Models\Traits\HasNameAttributes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Complainant
 *
 * @property int $complainant_id
 * @property string $last_name
 * @property string $first_name
 * @property string|null $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|Profession[] $complaintsProfessions
 * @property Collection|Department[] $complaintsDepartments
 * @property Collection|Customer[] $complaintsCustomers
 * @property Collection|Employee[] $complaintsEmployees
 * @property Collection|Customer[] $representationsCustomers
 * @property Collection|Complaint[] $complaints
 * @property Collection|Representation[] $representations
 */
class Complainant extends Model
{
    // Gère les initiales et le full name
    use HasNameAttributes;
    use HasFactory;


    protected $table = 'complainants';
    protected $connection = 'mysql';
    protected $primaryKey = 'complainant_id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'complainant_id',
        'complainant_type_id',
        'last_name',
        'first_name',
        'date_of_birth',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'zip_code',
    ];

    protected function casts(): array
    {
        return [
            'complainant_id' => 'integer',
            'complainant_type_id' => ComplainantType::class,
            'last_name' => 'string',
            'first_name' => 'string',
            'date_of_birth' => 'date',
            'email' => 'string',
            'phone' => 'string',
            'address' => 'string',
            'city' => 'string',
            'country' => 'string',
            'zip_code' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Complaint, $this>
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'complainant_id', 'complainant_id');
    }

    /**
     * @return HasMany<Representation, $this>
     */
    public function representations(): HasMany
    {
        return $this->hasMany(Representation::class, 'complainant_id', 'complainant_id');
    }

    /**
     * @return BelongsToMany<ObjectCategory, $this>
     */
    public function complaintsCategories(): BelongsToMany
    {
        return $this->belongsToMany(ObjectCategory::class, 'complaints', 'complainant_id', 'category_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'channel_id', 'profession_id', 'entity_id', 'customer_id', 'employee_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Channel, $this>
     */
    public function complaintsChannels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'complaints', 'complainant_id', 'channel_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'profession_id', 'entity_id', 'customer_id', 'employee_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Profession, $this>
     */
    public function complaintsProfessions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'complaints', 'complainant_id', 'profession_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'entity_id', 'customer_id', 'employee_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Department, $this>
     */
    public function complaintsDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'complaints', 'complainant_id', 'department_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'object_category_id', 'channel_id', 'profession_id', 'customer_id', 'employee_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Customer, $this>
     */
    public function complaintsCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'complaints', 'complainant_id', 'customer_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'object_category_id', 'channel_id', 'profession_id', 'department_id', 'employee_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Employee, $this>
     */
    public function complaintsEmployees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'complaints', 'complainant_id', 'employee_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'object_category_id', 'channel_id', 'profession_id', 'department_id', 'customer_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Customer, $this>
     */
    public function representationsCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'representations', 'complainant_id', 'customer_id')
            ->withTimestamps();
    }
}
