<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreIncidentRequest;
use App\Http\Requests\V1\UpdateIncidentRequest;
use App\Http\Resources\V1\IncidentCollection;
use App\Http\Resources\V1\IncidentResource;
use App\Models\Incident;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Info(
 *     title="Incidents API Documentation",
 *     version="0.1",
 * ),
 *  @OA\Server(
 *      description="",
 *      url="http://localhost/api/v1",
 *  ),
 * @OA\Schema(
 *     schema="Incident",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="courierId", type="integer", example=1),
 *     @OA\Property(property="clientId", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="Test Incident"),
 *     @OA\Property(property="reason", type="string", example="Test Reason"),
 *     @OA\Property(property="occurredAt", type="string", format="date-time", example="2024-04-15T12:00:00Z"),
 *     @OA\Property(property="deadline", type="string", format="date-time", example="2024-04-22T12:00:00Z"),
 * ),
 * @OA\SecurityScheme(
 *    securityScheme="bearerAuth",
 *    in="header",
 *    name="bearerAuth",
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT",
 * ),
 * @OA\Schema(
 *     schema="StoreIncidentRequest",
 *     required={"name", "reason", "occurred_at", "deadline"},
 *     @OA\Property(property="courierId", type="integer", example=1),
 *     @OA\Property(property="clientId", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="Test Incident"),
 *     @OA\Property(property="reason", type="string", example="Test Reason"),
 *     @OA\Property(property="occurredAt", type="string", format="date-time", example="2024-04-13T12:00:00Z"),
 *     @OA\Property(property="deadline", type="string", format="date-time", example="2024-04-20T12:00:00Z"),
 * ),
 *  @OA\Schema(
 *     schema="UpdateIncidentRequest",
 *     @OA\Property(property="courierId", type="integer", example=1),
 *     @OA\Property(property="clientId", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="Updated Incident"),
 *     @OA\Property(property="reason", type="string", example="Updated Reason"),
 *     @OA\Property(property="occurredAt", type="string", format="date-time", example="2024-04-15T12:00:00Z"),
 *     @OA\Property(property="deadline", type="string", format="date-time", example="2024-04-22T12:00:00Z"),
 * ),
 * @OA\Schema(
 *     schema="IncidentResource",
 *     @OA\Property(property="data", ref="#/components/schemas/Incident"),
 * ),
 * @OA\Schema(
 *     schema="PaginationLinks",
 *     type="object",
 *     @OA\Property(property="first", type="string", example="/incidents?page=1"),
 *     @OA\Property(property="last", type="string", example="/incidents?page=5"),
 *     @OA\Property(property="prev", type="string", example="/incidents?page=2"),
 *     @OA\Property(property="next", type="string", example="/incidents?page=4"),
 * ),
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     type="object",
 *     @OA\Property(property="current_page", type="integer", example=3),
 *     @OA\Property(property="from", type="integer", example=21),
 *     @OA\Property(property="to", type="integer", example=30),
 *     @OA\Property(property="per_page", type="integer", example=10),
 *     @OA\Property(property="total", type="integer", example=50),
 *     @OA\Property(property="last_page", type="integer", example=5),
 * )
 */
class IncidentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/incidents",
     *     summary="Create a new incident",
     *     tags={"Incidents"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreIncidentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Incident created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/IncidentResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(property="errors", type="object", example={"field_name": {"Error message"}})
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * ),
     */
    public function store(StoreIncidentRequest $request): JsonResource
    {
        return new IncidentResource(Incident::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/incidents",
     *     summary="Get all incidents",
     *     tags={"Incidents"},
     *     @OA\Response(
     *         response=200,
     *         description="List of incidents",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Incident")),
     *             @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
     *             @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     *         ),
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index(): ResourceCollection
    {
        return new IncidentCollection(Incident::paginate());
    }

    /**
     * @OA\Get(
     *     path="/incidents/{id}",
     *     summary="Get incident by ID",
     *     tags={"Incidents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the incident",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incident details",
     *         @OA\JsonContent(ref="#/components/schemas/IncidentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Incident not found"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show(Incident $incident): JsonResource
    {
        return new IncidentResource($incident);
    }

    /**
     * @OA\Put(
     *     path="/incidents/{id}",
     *     summary="Update incident by ID",
     *     tags={"Incidents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the incident",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateIncidentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
    *          description="Incident updated successfully",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Incident not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(property="errors", type="object", example={"field_name": {"Error message"}})
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(UpdateIncidentRequest $request, Incident $incident): void
    {
        $incident->update($request->all());
    }

    /**
     * @OA\Delete(
     *     path="/incidents/{id}",
     *     summary="Delete incident by ID",
     *     tags={"Incidents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the incident",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incident deleted successfully",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Incident not found"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy(Incident $incident): void
    {
        $incident->delete();
    }
}
