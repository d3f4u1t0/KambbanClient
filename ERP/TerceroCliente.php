<?php

namespace App\Modules\Crm\Models;

use App\Modules\General\Models\Tercero;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;

/**
 * Class TerceroCliente
 * @package App\Modules\Crm\Models
 * @author Ricardo Gonzalez <rgonzalez2520@gmail.com>
 */
class TerceroCliente extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * this parameter contains the model id for 'documentos' relationship
     */
    const MODEL_ID = 48;

    /**
     * this parameter returned the model name in database
     * @var string
     */
    protected $table = "terceros_clientes";

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        "id",
        "tercero_id",
        "clasificacioncliente_id",
        "activo",
        "created_at",
        "updated_at"
    ];

    /**
     * The attributes that aren't mass assignable.
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [];

    /**
     * Get the 'tercero' that owns the 'terceroCliente'.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tercero ()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id', 'id')
            ->select([
                'terceros.*',
                DB::raw("concat_ws(' ', terceros.razonsocial, terceros.primernombre, terceros.segundonombre, terceros.primerapellido, terceros.segundoapellido) as nombre_completo"),
            ])
            ->with('ciudad')
            ->with('tipoidentificacion')
            ->with('clasificaciontributariatercero')
            ->with('tipopersona')
            ->with('tercerosconceptostributarios')
            ->with('tercerossucursales')
            ->with('actividadeseconomicas')
            ->with(['documentos' => function ($query){
                $query->whereModeloId(self::MODEL_ID);
            }]);
    }

    /**
     * Get the 'clasificacioncliente' that owns the 'terceroCliente'.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clasificacioncliente ()
    {
        return $this->belongsTo(ClasificacionCliente::class, 'clasificacioncliente_id', 'id')
            ->select([
                'clasificaciones_clientes.id',
                'clasificaciones_clientes.nombre',
                'clasificaciones_clientes.activo'
            ]);
    }
}
