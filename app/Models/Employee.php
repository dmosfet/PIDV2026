<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasNameAttributes;
use Carbon\Carbon;
use App\Enums\ObjectCategory;
use App\Enums\DepartmentType;
use App\Enums\Channel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Employee
 *
 * @property int $employee_id
 * @property string $last_name
 * @property string $first_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $function
 * @property int $department_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Boolean $isActive
 * @property Collection|Complaint[] $complaints
 */
class Employee extends Model
{
    // Gère les initiales et le full name
    use HasNameAttributes;

    protected $table = 'employees';
    protected $connection = 'mysql';
    protected $primaryKey = 'employee_id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'function',
        'email',
        'phone',
        'department_id',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'employee_id' => 'integer',
            'last_name' => 'string',
            'first_name' => 'string',
            'function' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'department_id' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Retourne les plaintes dont l'employé est responsable du traitement
     *
     * @return HasMany<Complaint, $this>
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'employee_id', 'employee_id');
    }

    /**
     * Retourne le département dans lequel l'employé travaille.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Retourne le département dont l'employee est manager
     *
     * @return HasOne<Department, $this>
     */
    public function manager(): HasOne
    {
        return $this->hasOne(Department::class, 'manager_id', 'employee_id');
    }

}
