<?php

namespace App\Http\Controllers\Assignment;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\Controller;
use App\Repository\AssignmentRepository;
use App\Repository\WorkflowRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    protected $httpRequestResponse;
    protected $request;
    protected $assignmentRepository;
    protected $validator;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        AssignmentRepository $assignmentRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->assignmentRepository = $assignmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $request = $this->request->query();
        $data = $this->assignmentRepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }


    public function store()
    {
        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, [
            'request_id' => 'required|unique:assignments',
            'user_id' => 'required',
            'workflow' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->assignmentRepository->create($request);

        if (isset($create['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }
        if ($create->id) {
            if ($create) {
                $result[] = $create;
            }
            if (isset($createUser['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
        }

        return response()->json([
            'status' => $statusCode,
            'data' => $result
        ], $statusCode);
    }

    /**
     * Display the specified resource.
     *
     *
     * @return JsonResponse
     */
    public function find()
    {
        $request = $this->request->query();
        $data = $this->assignmentRepository->find($request['id']);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            'data' => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    /**
     * Update the specified resource in storage.
     *
     *
     *
     * @return JsonResponse
     */
    public function update()
    {
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $update = $this->assignmentRepository->update($request, $request['id']);

        if (isset($update->assignment->id)) {
            $updateAssignment = $this->assignmentRepository->update($request['values'], $update->assignment->id);
            if (isset($updateAssignment['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->assignmentRepository->find($request['id']);
        } else {
            $response[] = $update;
        }

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @return JsonResponse
     */
    public function destroy()
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $dataDelete = $this->assignmentRepository->find($request['id']);
        $deleteAssignment = $this->assignmentRepository->delete($dataDelete);

        if (isset($deleteAssignment['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteAssignment['id']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }
}
