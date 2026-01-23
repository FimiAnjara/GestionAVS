<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entite extends Model
{
    use SoftDeletes;

    protected $table = 'entite';
    protected $primaryKey = 'id_entite';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_entite',
        'nom',
        'description',
        'logo',
        'code_couleur',
        'id_groupe',
    ];

    /**
     * Get le groupe de l'entité
     */
    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'id_groupe', 'id_groupe');
    }

    /**
     * Get les sites de l'entité
     */
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class, 'id_entite', 'id_entite');
    }
}
