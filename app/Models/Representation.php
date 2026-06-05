<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Representation
 *
 * @property int $complainant_id
 * @property int $customer_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Complainant $complainant
 * @property Customer $customer
 */
class Representation extends Model
{
    protected $table = 'representations';

    /**
     * @var string
     */
    protected $connection = 'mysql';

    protected $primaryKey = 'complainant_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'complainant_id',
        'customer_id',
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
            'complainant_id' => 'integer',
            'customer_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Complainant, $this>
     */
    public function complainant(): BelongsTo
    {
        return $this->belongsTo(Complainant::class, 'complainant_id');
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
