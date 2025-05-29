<?php

namespace App\Http\Controllers\CustomizedPackages\NCDPPlus\CoreIndicators;

use App\Http\Controllers\Controller;
use App\Services\ProjectDataService;

class GetAllDataController extends Controller
{
    protected $projectDataService;

    public function __construct(ProjectDataService $projectDataService)
    {
        $this->projectDataService = $projectDataService;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(int $projectId, string $status, string $condition)
    {
        //
        if ($status == 'activepatients') {
            $data = $this->projectDataService->getAllActive($projectId, $condition)->flatten(2);
        }
        if ($status == 'femalepatients') {
            $data = $this->projectDataService->getAllFemaleActive($projectId, $condition)->flatten(2);
        }
        if ($status == 'lastthreemonths') {
            $data = $this->projectDataService->getAllNewlyEnrolled($projectId, $condition)->flatten(2);
        }
        if ($status == 'lastthreemonthsltfu') {
            $data = $this->projectDataService->getAllNewlyLTFU($projectId, $condition)->flatten(2);
        }
        if ($status == 'mortalitythreemonths') {
            $data = $this->projectDataService->getAllNewlyDeaths($projectId, $condition)->flatten(2);
        }
        if (empty($data) || $data->isEmpty()) {
            return response()->json(['message' => 'No data found.'], 404);
        }

        return response()->json(['data' => $data], 200);

    }
}
