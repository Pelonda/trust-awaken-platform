<?php

declare(strict_types=1);

namespace App\Core\Organization\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Organization extends Model
{
    use SoftDeletes;

    protected $table = 'organizations';

    /**
     * Primary key.
     */
    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * DTOs and Actions control persistence.
     */
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
            'deleted_at'   => 'datetime',
            'activated_at' => 'datetime',
            'archived_at'  => 'datetime',
        ];
    }

    /**
     * Organization owner.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'owner_user_id'
        );
    }
}