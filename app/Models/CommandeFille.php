<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommandeFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'commandeFille';
    protected $primaryKey = 'id_commandeFille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_commandeFille', 'quantite', 'id_unite', 'id_commande', 'id_article'];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande', 'id_commande');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class, 'id_unite', 'id_unite');
    }
}
