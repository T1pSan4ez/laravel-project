<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSessionSlotRequest;
use App\Repositories\SessionSlotRepositoryInterface;

class SessionSlotController extends Controller
{
    protected $sessionSlotRepository;

    public function __construct(SessionSlotRepositoryInterface $sessionSlotRepository)
    {
        $this->sessionSlotRepository = $sessionSlotRepository;
    }

    public function updateStatuses($session_id, UpdateSessionSlotRequest $request)
    {
        $validated = $request->validated();

        $updatedSlots = $this->sessionSlotRepository->updateSlotStatuses($session_id, $validated['slots']);

        return response()->json([
            'message' => 'Statuses updated successfully',
            'data' => $updatedSlots,
        ]);
    }
}
