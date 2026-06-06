<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tally\OpeningClosingRequest;
use App\Http\Requests\Tally\PartySyncRequest;
use App\Http\Requests\Tally\SalesBillRequest;
use App\Models\TallyOpeningClosing;
use App\Models\TallyPartySync;
use App\Models\TallySalesBill;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class TallyController extends Controller
{
    public function partySync(PartySyncRequest $request): JsonResponse
    {
        try {
            $partySync = TallyPartySync::create($this->payloadWithRawPayload($request->validated(), $request->all()));

            return $this->successResponse($partySync->id);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception, 'party-sync');
        }
    }

    public function salesBill(SalesBillRequest $request): JsonResponse
    {
        try {
            $salesBill = TallySalesBill::create($this->payloadWithRawPayload($request->validated(), $request->all()));

            return $this->successResponse($salesBill->id);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception, 'sales-bill');
        }
    }

    public function openingClosing(OpeningClosingRequest $request): JsonResponse
    {
        try {
            $openingClosing = TallyOpeningClosing::create($this->payloadWithRawPayload($request->validated(), $request->all()));

            return $this->successResponse($openingClosing->id);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception, 'opening-closing');
        }
    }

    private function payloadWithRawPayload(array $validated, array $payload): array
    {
        return array_merge($validated, [
            'raw_payload' => $payload,
        ]);
    }

    private function successResponse(int $id): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Data received successfully',
            'id' => $id,
        ]);
    }

    private function errorResponse(Throwable $exception, string $endpoint): JsonResponse
    {
        Log::error('Tally integration failed.', [
            'endpoint' => $endpoint,
            'message' => $exception->getMessage(),
            'exception' => $exception,
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
        ], 500);
    }
}
