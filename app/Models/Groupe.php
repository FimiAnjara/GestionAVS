<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Groupe extends Model
{
    use SoftDeletes;

    protected $table = 'groupe';
    protected $primaryKey = 'id_groupe';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_groupe',
        'nom',
    ];

    /**
     * Get les entités du groupe
     */
    public function entites(): HasMany
    {
        return $this->hasMany(Entite::class, 'id_groupe', 'id_groupe');
    }
}
