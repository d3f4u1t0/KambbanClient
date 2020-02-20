<?php

namespace App\Modules\Crm\Repository;

use App\Interfaces\RepositoriesInterface;
use App\Modules\Crm\Models\TerceroCliente;
use App\Traits\RepositoryTrait;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class TerceroClienteRepository
 * @package App\Modules\Crm\Repository
 * @author Ricardo Gonzalez <rgonzalez2520@gmail.com>
 */
class TerceroClienteRepository implements RepositoriesInterface
{
    use RepositoryTrait;

    /**
     * @var \App\Modules\Crm\Models\TerceroCliente
     */
    private $model;

    /**
     * this constant set ordering type
     */
    const ORDERING = [
        'ascending' => 'asc',
        'descending' => 'desc'
    ];

    /**
     * this constant contiain all DB fields
     */
    const FIELDS = [
        'terceros_clientes.id',
        'terceros_clientes.tercero_id',
        'terceros_clientes.clasificacioncliente_id',
        'terceros_clientes.activo',
        'terceros_clientes.created_at',
        'terceros_clientes.updated_at'
    ];

    /**
     * TerceroClienteRepository constructor.
     * @param \App\Modules\Crm\Models\TerceroCliente $terceroCliente
     */
    public function __construct(TerceroCliente $terceroCliente)
    {
        $this->model = $terceroCliente;
    }

    /**
     * this function return all data with yours respective fields, relationships and withs eloquent
     * @param $paginate
     * @return array
     */
    public function all($paginate)
    {
        $totaldata  = $this->model->count();

        $query = $this->model
            ->select(self::FIELDS);

        $query->when(isset($paginate['sorts']), function ($q) use ($paginate) {
            $sorts = json_decode($paginate['sorts'], true);
            $order = key($sorts);
            $direction = $sorts[$order];
            return $q->orderBy($order, self::ORDERING[$direction]);
        });

        $query->when(isset($paginate['search']), function($q) use ($paginate) {
            $search = $paginate['search'];

            return $q->whereHas('tercero', function ($qu) use ($search) {
                $qu->where('primernombre', 'like', "%{$search}%")
                    ->orWhere('segundonombre', 'like', "%{$search}%")
                    ->orWhere('primerapellido', 'like', "%{$search}%")
                    ->orWhere('segundoapellido', 'like', "%{$search}%")
                    ->orWhere('nombrecomercial', 'like', "%{$search}%")
                    ->orWhere('razonsocial', 'like', "%{$search}%");
            });
        });

        $query->when(isset($paginate['rowsPerPage'])&&isset($paginate['page']), function($q) use ($paginate) {
            return $q
                ->skip($paginate['page'])
                ->take($paginate['rowsPerPage']);
        });

        $query->when(isset($paginate['activos']), function($q) {
            return $q->whereActivo('1');
        });

        $query
            ->with('tercero')
            ->with('clasificacioncliente');

        $query  = $query->get();
        $totalFiltered  = $query->count();

        $json_response = [
            "recordsTotal"      => $totaldata,
            "recordsFiltered"   => $totalFiltered,
            "data"              => $query->toArray()
        ];

        return $json_response;
    }

    /**
     * this function return one row of DB with yours respective fields, relationships and withs eloquent
     * @param $id
     * @return array
     */
    public function find($id)
    {
        try {
            return $this->model
                ->select(self::FIELDS)
                ->where('terceros_clientes.id', '=', $id)
                ->with('tercero')
                ->with('clasificacioncliente')
                ->first();
        } catch (ModelNotFoundException $ex) {
            return [
                'message' => 'No se ha encontrado'
            ];
        }
    }

    /**
     * this function crated a new row in DB
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        try{
            $result = $this->model->create($data);
            return $this->find($result->id);
        } catch (QueryException $qe) {
            return [
                'message' => 'Ocurrio un error' . $qe
            ];
        }

    }

    /**
     * this function updated a row in DB
     * @param array $data
     * @param $id
     * @return array
     */
    public function update(array $data, $id)
    {
        try {
            $result = $this->model->findOrFail($id);
            $result->update($data);
            return $this->find($id);
        } catch (ModelNotFoundException $ex) {
            return [
                'message' => 'No se ha encontrado'
            ];
        }
    }
}
