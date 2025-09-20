<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\FailedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class FailedJobController extends BaseController
{
    /**
     * Display a listing of failed jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $failedJobs = FailedJob::orderBy('failed_at', 'desc')->paginate(15);
        return $this->sendResponse($failedJobs, 'Failed jobs retrieved successfully.');
    }

    /**
     * Store a new failed job record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'connection' => 'required|string',
            'queue' => 'required|string',
            'payload' => 'required|string',
            'exception' => 'required|string',
            'failed_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $uuid = Uuid::uuid4(); // Version 4 UUID (random)

        $failedJob = FailedJob::create([
            'uuid' => $uuid->toString(),
            'connection' => $request->connection,
            'queue' => $request->queue,
            'payload' => $request->payload,
            'exception' => $request->exception,
            'failed_at' => now()
        ]);

        return $this->sendResponse($failedJob, 'Failed job recorded successfully.');
    }

    /**
     * Display the specified failed job.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $failedJob = FailedJob::where('uuid', $uuid)->first();

        if (is_null($failedJob)) {
            return $this->sendError('Failed job not found.');
        }

        return $this->sendResponse($failedJob, 'Failed job retrieved successfully.');
    }

    /**
     * Remove the specified failed job.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $failedJob = FailedJob::where('uuid', $uuid)->first();

        if (is_null($failedJob)) {
            return $this->sendError('Failed job not found.');
        }

        $failedJob->delete();

        return $this->sendResponse([], 'Failed job deleted successfully.');
    }
}
