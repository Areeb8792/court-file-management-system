<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\CourtCase;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request)
    {
        $request->validate([
            'court_case_id' => 'required|exists:court_cases,id',
            'name' => 'required|string|max:255',
            'document_type' => 'required|string|in:fir,petition,evidence,motion,judgment',
            'file' => 'required|file|mimes:pdf,png,jpg,jpeg,docx|max:12288', // Max 12MB
        ]);

        $case = CourtCase::findOrFail($request->court_case_id);
        $file = $request->file('file');

        // Store file in the 'public' disk under 'documents'
        $path = $file->store('documents', 'public');

        DB::transaction(function () use ($request, $case, $file, $path) {
            $document = Document::create([
                'court_case_id' => $case->id,
                'user_id' => auth()->id(),
                'name' => $request->name . '.' . $file->getClientOriginalExtension(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'document_type' => $request->document_type,
                'version' => 1,
            ]);

            // Advance case status if evidence is submitted
            if ($request->document_type === 'evidence' && $case->status === 'hearing_scheduled') {
                $case->update(['status' => 'evidence_submitted']);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'court_case_id' => $case->id,
                'action' => 'File Uploaded',
                'details' => "Uploaded document: {$document->name} (Type: " . ucfirst($request->document_type) . ").",
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('cases.show', $case)->with('success', 'Document uploaded and registered successfully.');
    }

    /**
     * Upload a new version of an existing document.
     */
    public function uploadVersion(Request $request, Document $document)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,png,jpg,jpeg,docx|max:12288', // Max 12MB
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');
        $case = CourtCase::findOrFail($document->court_case_id);

        // Get the latest version number
        $latestVersion = Document::where('parent_id', $document->id)
            ->orWhere('id', $document->id)
            ->max('version');

        DB::transaction(function () use ($document, $file, $path, $latestVersion, $case, $request) {
            // Create the new document version
            $newDoc = Document::create([
                'court_case_id' => $document->court_case_id,
                'user_id' => auth()->id(),
                'name' => $document->name, // Keep identical name
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'document_type' => $document->document_type,
                'version' => $latestVersion + 1,
                'parent_id' => $document->id, // Parent points to initial v1
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'court_case_id' => $case->id,
                'action' => 'File Version Uploaded',
                'details' => "Uploaded version " . ($latestVersion + 1) . " of document: {$document->name}.",
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('cases.show', $case)->with('success', "New version of {$document->name} uploaded successfully.");
    }

    /**
     * Download the specified document file.
     */
    public function download(Document $document)
    {
        // Check if file exists in storage
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found on storage disk.');
        }

        return Storage::disk('public')->download($document->file_path, $document->name);
    }

    /**
     * Preview the specified document file inline.
     */
    public function preview(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found on storage disk.');
        }

        return Storage::disk('public')->response($document->file_path);
    }
}
