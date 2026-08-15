<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentVersion;
use App\Models\TrackedItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentVersionController extends Controller
{
    public function index(Request $request, string $trackedItem): JsonResponse
    {
        $company = $request->user()->companies()->firstOrFail();
        
        $item = TrackedItem::where('company_id', $company->id)->findOrFail($trackedItem);
        abort_unless($request->user()->can('view', $item), 403);

        $versions = $item->versions()->orderByDesc('version_number')->get();

        return response()->json([
            'data' => $versions,
        ]);
    }

    public function store(Request $request, string $trackedItem): JsonResponse
    {
        $company = $request->user()->companies()->firstOrFail();
        abort_unless($request->user()->can('create', [DocumentVersion::class, $company->id]), 403);
        $item = TrackedItem::where('company_id', $company->id)->findOrFail($trackedItem);

        // Determine max file size based on company plan
        $maxSizeBytes = $this->getMaxUploadSizeForCompany($company);
        $maxSizeKB = intval($maxSizeBytes / 1024);

        $request->validate([
            'file' => [
                'required',
                'file',
                'max:' . $maxSizeKB,
                'mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx'
            ],
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        
        $path = $file->store("documents/{$company->id}/{$item->id}", 'local');
        
        $versionNumber = $item->versions()->max('version_number') + 1;

        $documentVersion = DocumentVersion::create([
            'tracked_item_id' => $item->id,
            'version_number' => $versionNumber,
            'file_disk' => 'local',
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => basename($path),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'sha256_hash' => hash_file('sha256', $file->getRealPath()),
            'uploaded_by' => $request->user()->id,
            'uploaded_at' => now(),
            'comment' => $request->string('comment'),
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully.',
            'data' => $documentVersion,
        ], 201);
    }
    
    public function show(Request $request, string $trackedItem, string $version): JsonResponse
    {
        $company = $request->user()->companies()->firstOrFail();
        $item = TrackedItem::where('company_id', $company->id)->findOrFail($trackedItem);
        $documentVersion = $item->versions()->findOrFail($version);
        
        abort_unless($request->user()->can('view', $documentVersion), 403);
        
        // Generate a temporary signed URL for downloading
        $url = URL::temporarySignedRoute(
            'documents.download', 
            now()->addMinutes(15),
            ['trackedItem' => $item->id, 'version' => $documentVersion->id]
        );
        
        return response()->json([
            'data' => [
                'download_url' => $url,
                'document' => $documentVersion
            ]
        ]);
    }
    
    public function download(Request $request, string $trackedItem, string $version): StreamedResponse
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired download link.');
        }
        
        // Even with signed route, verify tenant isolation
        $company = $request->user()->companies()->firstOrFail();
        $item = TrackedItem::where('company_id', $company->id)->findOrFail($trackedItem);
        $documentVersion = $item->versions()->findOrFail($version);
        
        if (!Storage::disk($documentVersion->file_disk)->exists($documentVersion->file_path)) {
            abort(404, 'File not found.');
        }
        
        return Storage::disk($documentVersion->file_disk)->download(
            $documentVersion->file_path, 
            $documentVersion->original_filename
        );
    }

    private function getMaxUploadSizeForCompany(\App\Models\Company $company): int
    {
        $plan = $company->subscription_status ?? 'trial';
        
        return match($plan) {
            'trial' => 5 * 1024 * 1024, // 5MB
            'basic' => 10 * 1024 * 1024, // 10MB
            'pro' => 50 * 1024 * 1024, // 50MB
            default => 5 * 1024 * 1024,
        };
    }
}
