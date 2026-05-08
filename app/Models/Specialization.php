<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Specialization Model
 *
 * Manages doctor specializations with proper PascalCase naming convention.
 * All relationships use proper model class references in PascalCase.
 *
 * @refactor Added documentation and standardized naming conventions
 */
class Specialization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Get the doctors for the specialization.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
