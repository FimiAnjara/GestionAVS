<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeEvaluationStock extends Model
{
    use SoftDeletes;

    protected $table = 'type_evaluation_stock';
    protected $primaryKey = 'id_type_evaluation_stock';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_type_evaluation_stock',
        'libelle',
        'description',
    ];

    /**
     * Get les articles qui utilisent ce type d'évaluation.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'id_type_evaluation_stock', 'id_type_evaluation_stock');
    }
}
