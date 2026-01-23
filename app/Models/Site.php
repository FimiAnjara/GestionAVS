<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use SoftDeletes;

    protected $table = 'site';
    protected $primaryKey = 'id_site';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_site',
        'localisation',
        'id_entite',
    ];

    /**
     * Get l'entité du site
     */
    public function entite(): BelongsTo
    {
        return $this->belongsTo(Entite::class, 'id_entite', 'id_entite');
    }

    /**
     * Get les magasins du site
     */
    public function magasins(): HasMany
    {
        return $this->hasMany(Magasin::class, 'id_site', 'id_site');
    }
}
