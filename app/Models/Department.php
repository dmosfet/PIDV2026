<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DepartmentType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Entity
 *
 * @property int $department_id
 * @property string $name
 * @property string $code
 * @property string $address
 * @property string $city
 * @property string $zip_code
 * @property int|null $manager_id
 * @property int $department_type_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Employee $employee
 * @property DepartmentType $departmentType
 * @property Collection|Profession[] $complaintsProfessions
 * @property Collection|Customer[] $complaintsCustomers
 * @property Collection|Employee[] $complaintsEmployees
 * @property Collection|Complainant[] $complaintsComplainants
 * @property Collection|Complaint[] $complaints
 */
class Department extends Model
{
    protected $table = 'departments';
    protected $connection = 'mysql';
    protected $primaryKey = 'department_id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'department_id',
        'name',
        'code',
        'address',
        'city',
        'zip_code',
        'manager_id',
        'department_type_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'department_id' => 'integer',
            'name' => 'string',
            'code'=> 'string',
            'address' => 'string',
            'city' => 'string',
            'zip_code' => 'string',
            'manager_id' => 'integer',
            'department_type_id' => DepartmentType::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Complaint, $this>
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'department_id', 'department_id');
    }

    /**
     * @return BelongsTo<Employee, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'employee_id');
    }

    /**
     * @return BelongsTo<DepartmentType, $this>
     */
    public function entityType(): BelongsTo
    {
        return $this->belongsTo(DepartmentType::class, 'department_type_id', 'department_type_id');
    }

    /**
     * @return BelongsToMany<Profession, $this>
     */
    public function complaintsProfessions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'complaints', 'department_id', 'profession_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'customer_id', 'employee_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Customer, $this>
     */
    public function complaintsCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'complaints', 'department_id', 'customer_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'profession_id', 'employee_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Employee, $this>
     */
    public function complaintsEmployees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'complaints', 'department_id', 'employee_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'profession_id', 'customer_id', 'complainant_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Complainant, $this>
     */
    public function complaintsComplainants(): BelongsToMany
    {
        return $this->belongsToMany(Complainant::class, 'complaints', 'department_id', 'complainant_id')
            ->withPivot('status', 'reception_date', 'admissible', 'well_founded', 'acknowledgment_date', 'transmission_date', 'response', 'response_date', 'duration', 'category_id', 'channel_id', 'profession_id', 'customer_id', 'employee_id')
            ->withTimestamps();
    }
}
