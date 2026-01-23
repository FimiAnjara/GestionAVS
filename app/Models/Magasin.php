<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Magasin extends Model
{
    use SoftDeletes;
    
    protected $table = 'magasin';
    protected $primaryKey = 'id_magasin';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_magasin',
        'nom',
        'longitude',
        'latitude',
        'id_site',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get le site du magasin
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'id_site', 'id_site');
    }
}
