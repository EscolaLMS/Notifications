<?php

namespace EscolaLms\Notifications\Http\Controllers;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Notifications\Http\Requests\NotificationsRequest;
use EscolaLms\Notifications\Http\Resources\NotificationResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends EscolaLmsBaseController
{
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
     *      @OA\Parameter(
     *          name="user",
     *          description="User Id (if current user does not have permissions required to see other users notifications, this endpoint will default to returning only current user notifications)",
     *          required=false,
     *          in="route",
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

    /**
     * @OA\Schema(
     *      schema="Notification",
     *      @OA\Property(
     *          property="id",
     *          description="notification uuid",
     *          type="integer",
     *      ),
     *      @OA\Property(
     *          property="type",
     *          description="notification full classname",
     *          type="string"
     *      ),
     *      @OA\Property(
     *          property="event",
     *          description="event full classname",
     *          type="string"
     *      ),
     *      @OA\Property(
     *          property="notifiable_type",
     *          description="class representing User that got the notification",
     *          type="string"
     *      ),
     *      @OA\Property(
     *          property="notifiable_id",
     *          description="id of User that got the notification",
     *          type="string"
     *      ),
     *      @OA\Property(
     *          property="data",
     *          description="all notification data",
     *          type="object"
     *      ),
     *      @OA\Property(
     *          property="read_at",
     *          description="timestamp when notification was marked as read",
     *          type="datetime"
     *      ),
     * )
     */
    public function index(NotificationsRequest $request)
    {
        /** @var LengthAwarePaginator $notifications */
        $notifications = $request->getUser()->notifications()->paginate();
        return $this->sendResponseForResource(NotificationResource::collection($notifications));
    }
}
