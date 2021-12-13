<?php

namespace EscolaLms\Notifications\Http\Controllers;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Notifications\Http\Requests\NotificationEventsRequest;
use EscolaLms\Notifications\Http\Requests\NotificationsRequest;
use EscolaLms\Notifications\Http\Requests\NotificationsUserRequest;
use EscolaLms\Notifications\Http\Resources\NotificationResource;
use EscolaLms\Notifications\Services\Contracts\DatabaseNotificationsServiceContract;

class NotificationsController extends EscolaLmsBaseController
{
    private DatabaseNotificationsServiceContract $service;

    public function __construct(DatabaseNotificationsServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *      path="/api/notifications",
     *      summary="Get notifications",
     *      tags={"Notifications"},
     *      description="Get paginated list of notifications sent using `database` channel",
     *      @OA\Parameter(
     *          name="page",
     *          description="Pagination Page Number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *               default=1,
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          description="Pagination Per Page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *               default=15,
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          ),
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Notification")
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */

    /**
     * @OA\Get(
     *      path="/api/admin/notifications/:user",
     *      summary="Get notifications",
     *      tags={"Notifications Admin"},
     *      description="Get paginated list of notifications sent using `database` channel",
     *      @OA\Parameter(
     *          name="page",
     *          description="Pagination Page Number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *               default=1,
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          description="Pagination Per Page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *               default=15,
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="event",
     *          description="Event class filter",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="user",
     *          description="User Id (if empty, will return all notifications)",
     *          required=false,
     *          in="path",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          ),
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Notification")
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */

    public function index(NotificationsRequest $request)
    {
        $notifications = $this->service->getAllNotifications($request->getEvent());
        return $this->sendResponseForResource(NotificationResource::collection($notifications));
    }

    public function user(NotificationsUserRequest $request)
    {
        $notifications = $this->service->getUserNotifications($request->getUser(), $request->getEvent());
        return $this->sendResponseForResource(NotificationResource::collection($notifications));
    }

    /**
     * @OA\Get(
     *      path="/api/notifications/events",
     *      summary="Get list of events for which notifications exist",
     *      tags={"Notifications"},
     *      description="Get list of events for which notifications exist",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          ),
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(@OA\Schema(type="string"))
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */

    /**
     * @OA\Get(
     *      path="/api/admin/notifications/events",
     *      summary="Get list of events for which notifications exist",
     *      tags={"Notifications Admin"},
     *      description="Get list of events for which notifications exist",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          ),
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(@OA\Schema(type="string"))
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function events(NotificationEventsRequest $request)
    {
        return $this->sendResponse($this->service->getEvents());
    }
}
