<?php

declare(strict_types=1);

namespace App\Modules\Media\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Media\Models\Media;
use App\Modules\Media\Models\MediaFolder;
use App\Modules\Media\Contracts\MediaServiceInterface;
use App\Modules\Media\Actions\CreateFolderAction;
use App\Modules\Media\Http\Requests\UploadMediaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(
        protected MediaServiceInterface $mediaService,
        protected CreateFolderAction $createFolderAction
    ) {}

    /**
     * Display the media manager index page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $folderId = $request->get('folder_id') ? (int) $request->get('folder_id') : null;

        $currentFolder = null;
        $parentFolderId = null;

        if ($folderId) {
            $currentFolder = MediaFolder::findOrFail($folderId);
            $parentFolderId = $currentFolder->parent_id;
        }

        $folders = MediaFolder::where('parent_id', $folderId)->orderBy('name')->get();
        $media = Media::where('folder_id', $folderId)->orderBy('created_at', 'desc')->get();

        return view('media::admin.index', compact('folders', 'media', 'currentFolder', 'parentFolderId'));
    }

    /**
     * Store a newly uploaded media.
     *
     * @param UploadMediaRequest $request
     * @return RedirectResponse
     */
    public function store(UploadMediaRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        $folderId = $request->filled('folder_id') ? (int) $request->input('folder_id') : null;
        $caption = $request->input('caption');
        $altText = $request->input('alt_text');

        try {
            $this->mediaService->upload($file, $folderId, $caption, $altText);
            return redirect()->back()->with('success', 'Media uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => $e->getMessage()]);
        }
    }

    /**
     * Create a virtual folder.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function createFolder(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:media_folders,id',
        ]);

        $name = $request->input('name');
        $parentId = $request->filled('parent_id') ? (int) $request->input('parent_id') : null;

        try {
            $this->createFolderAction->execute($name, $parentId);
            return redirect()->back()->with('success', 'Folder created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['name' => $e->getMessage()]);
        }
    }

    /**
     * Soft delete a media file.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->mediaService->delete($id);
        return redirect()->back()->with('success', 'Media deleted successfully.');
    }

    /**
     * Soft delete a folder and all its contents recursively.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroyFolder(int $id): RedirectResponse
    {
        $folder = MediaFolder::findOrFail($id);
        
        // Soft delete child folders
        $folder->children()->delete();
        // Soft delete media files
        $folder->media()->delete();
        // Soft delete folder
        $folder->delete();

        return redirect()->back()->with('success', 'Folder deleted successfully.');
    }

    /**
     * Handle AJAX file upload from rich text editor (Trix).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function richtextUpload(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => 'required|file|image|max:10240', // Limit to 10MB
        ]);

        try {
            $media = $this->mediaService->upload($request->file('file'));
            $url = $this->mediaService->getUrl($media->id);
            return response()->json([
                'image_url' => $url,
                'media_id' => $media->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
