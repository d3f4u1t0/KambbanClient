<?php

namespace App\Modules\Crm\Http\Controllers;

use App\Modules\Crm\Validator\TerceroClienteValidator;
use App\Modules\General\Repository\TerceroRepository;
use App\Modules\Crm\Repository\TerceroClienteRepository;
use App\Modules\General\Validator\TerceroValidator;
use Illuminate\Http\Request;
use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\Controller;

/**
 * Class TerceroClienteController
 * @package App\Modules\Crm\Http\Controllers
 * @author Ricardo Gonzalez <rgonzalez2520@gmail.com>
 */
class TerceroClienteController extends Controller
{
    /**
     * this parameter contains the http request responses
     * @var
     */
    private $httpRequestResponse;

    /**
     * this parameter contain all request
     * @var
     */
    private $request;

    /**
     * this parameter contains all model logic
     * @var
     */
    private $repository;

    /**
     * this parameter contains the validator for request
     * @var
     */
    private $validator;

    /**
     * this parameter contains the 'cliente' validator for request
     * @var
     */
    protected $validatorcliente;

    /**
     * this parameter contains all 'TerceroCliente' model logic
     * @var
     */
    protected $terceroclienterepository;

    /**
     * TerceroClienteController constructor.
     * @param \Illuminate\Http\Request $request
     * @param \App\Helpers\HttpRequestResponse $httpRequestResponse
     * @param \App\Modules\General\Repository\TerceroRepository $terceroRepository
     * @param \App\Modules\Crm\Repository\TerceroClienteRepository $terceroClienteRepository
     * @param \App\Modules\General\Validator\TerceroValidator $terceroValidator
     * @param \App\Modules\Crm\Validator\TerceroClienteValidator $terceroClienteValidator
     */
    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        TerceroRepository $terceroRepository,
        TerceroClienteRepository $terceroClienteRepository,
        TerceroValidator $terceroValidator,
        TerceroClienteValidator $terceroClienteValidator
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->repository = $terceroRepository;
        $this->terceroclienterepository = $terceroClienteRepository;
        $this->validator = $terceroValidator;
        $this->validatorcliente = $terceroClienteValidator;
    }

    /**
     * @OA\Get(
     *     path="/crm/clientes",
     *     summary="Endpoint para recuperar todas los clientes",
     *     tags={"Crm"},
     *     description="Obtiene los registros paginados segun especificaciones del Json",
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="rowsPerPage",
     *         in="query",
     *         description="(Opcional) - filas por pagina",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="(Opcional) - Desde que fila extraer los registros",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="activos",
     *         in="query",
     *         description="(Opcional) - Envie 1 si desea filtrar solo los clientes activos",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="(Opcional) - Envie una cadena para buscar por nombre o apellido un tercero",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     **/

    public function index()
    {
        $request = $this->request->query();

        $data = $this->terceroclienterepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    /**
     * @OA\Get(
     *     path="/crm/clientes/id",
     *     summary="Endpoint para recuperar un cliene según su id",
     *     tags={"Crm"},
     *     description="",
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Id del registro que desea consultar",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     **/

    public function find()
    {
        $request = $this->request->query();

        $data = $this->terceroclienterepository->find($request['id']);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            'data' => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    /**
     * @OA\Post(
     *     path="/crm/clientes/update",
     *     summary="Endpoint para actualizar los clientes según su id",
     *     tags={"Crm"},
     *     description="Actualiza de forma masiva, puede enviar uno o varios arreglos para su actualizacion, verificar Json",
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="Body",
     *                     type="string"
     *                 ),
     *                 example={{"id":1,"values":{"numeroidentificacion":1233556,"primernombre":"primernombre","segundonombre":"segundonombre","primerapellido":"primerapellido","segundoapellido":"segundoapellido","nombrecomercial":"nombrecomercial","razonsocial":"razonsocial","digitoverificacion":1,"telefono1":"telefono1","telefono2":"telefono2","fax":"fax","email":"email@email.com","geolocalizacion":"geolocalizacion","direccion":"direccion","tipoidentificacion_id":1,"clasificacioncliente_id":1,"ciudad_id":1,"activo":0}}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     **/

    public function update()
    {
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        foreach ($request as $data) {
            $update = $this->terceroclienterepository->update($data['values'], $data['id']);

            if(isset($update->tercero->id)) {
                $updatetercerocliente = $this->repository->update($data['values'], $update->tercero->id);

                if(isset($updatetercerocliente['error'])) {
                    $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                    break;
                }

                $response[] = $this->terceroclienterepository->find($data['id']);
            } else {
                $response[] = $update;
            }
        }

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }

    /**
     * @OA\Delete(
     *     path="/crm/clientes/delete",
     *     summary="Endpoint para elimiar un cliente según su id",
     *     tags={"Crm"},
     *     description="Elimina de forma masiva, puede enviar uno o varios arreglos para su actualizacion, verificar Json",
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="Body",
     *                     type="string"
     *                 ),
     *                 example={{"id":1},{"id":2}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     **/

    public function destroy()
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();

        foreach ($request as $data) {
            $datadelete = $this->terceroclienterepository->find($data['id']);

            $deleteTerceroCliente = $this->terceroclienterepository->delete($data['id']);

            if(isset($deleteTerceroCliente['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                break;
            }

            $deletetercero = $this->repository->delete($datadelete[0]['tercero_id']);

            if(isset($deletetercero['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                break;
            }

            $response[] = "Eliminado: {$deleteTerceroCliente}" ;
        }

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }

    /**
     * @OA\Post(
     *     path="/crm/clientes/create",
     *     summary="Endpoint para crear los clientes",
     *     tags={"Crm"},
     *     description="Crea, verificar Json",
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="Body",
     *                     type="string"
     *                 ),
     *                 example={{"numeroidentificacion":1233556,"primernombre":"primernombre","segundonombre":"segundonombre","primerapellido":"primerapellido","segundoapellido":"segundoapellido","nombrecomercial":"nombrecomercial","razonsocial":"razonsocial","digitoverificacion":1,"telefono1":"telefono1","telefono2":"telefono2","fax":"fax","email":"email@email.com","geolocalizacion":"geolocalizacion","direccion":"direccion","tipoidentificacion_id":1,"clasificacioncliente_id":1,"ciudad_id":1,"clasificaciontributariatercero_id":1,"activo":1}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     **/

    public function store()
    {
        $result = [];
        $request = $this->request->json()->all();
        $statuscode = $this->httpRequestResponse->getResponseOk();

        $validator = $this->validator->validator($request);

        if($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $validatorcliente = $this->validatorcliente->validator($request);

        if($validatorcliente->fails()) {
            return response()->json(['message' => $validatorcliente->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        foreach($request as $data) {

            $create = $this->repository->create($data);

            if(isset($create['error'])) {
                $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
                break;
            }

            if($create->id) {
                $data['tercero_id'] = $create->id;

                $createTercero = $this->terceroclienterepository->create($data);

                if($createTercero) {
                    $result[] = $createTercero;
                }

                if(isset($createTercero['error'])) {
                    $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
                    break;
                }

            }
        }

        return response()->json([
            'status' => $statuscode,
            'data' => $result
        ], $statuscode);
    }
}
