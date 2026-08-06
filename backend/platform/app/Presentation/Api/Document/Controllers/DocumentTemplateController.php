<?php

declare(strict_types=1);

namespace App\Presentation\Api\Document\Controllers;

use App\Core\Document\Repositories\DocumentTemplateRepository;
use App\Core\Document\Services\DocumentTemplateService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DocumentTemplateController extends Controller
{
    public function __construct(
        private readonly DocumentTemplateRepository $templates,
        private readonly DocumentTemplateService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->templates->paginate()
        );
    }

    public function show(string $uuid): JsonResponse
    {
        $template = $this->templates->findByUuid($uuid);

        abort_if($template === null, 404);

        return response()->json([
            'data' => $template,
        ]);
    }

    public function store(
        Request $request,
    ): JsonResponse {

        $data = $request->validate([

            'organization_id' => [
                'nullable',
                'integer',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'type' => [
                'required',
                'string',
            ],

            'paper_size' => [
                'required',
                'string',
            ],

            'orientation' => [
                'required',
                'string',
            ],

            'canvas' => [
                'nullable',
                'array',
            ],

            'default' => [
                'boolean',
            ],

        ]);

        return response()->json([
            'data' => $this->service->create($data),
        ], 201);
    }

    public function update(
        Request $request,
        string $uuid,
    ): JsonResponse {

        $template =
            $this->templates->findByUuid($uuid);

        abort_if($template === null, 404);

        $data = $request->validate([

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'paper_size' => [
                'required',
                'string',
            ],

            'orientation' => [
                'required',
                'string',
            ],

            'canvas' => [
                'nullable',
                'array',
            ],

            'default' => [
                'boolean',
            ],

        ]);

        return response()->json([
            'data' => $this->service->update(
                $template,
                $data,
            ),
        ]);
    }

    public function destroy(
        string $uuid,
    ): JsonResponse {

        $template =
            $this->templates->findByUuid($uuid);

        abort_if($template === null, 404);

        $this->templates->delete($template);

        return response()->json([
            'message' => 'Template deleted.',
        ]);
    }
}