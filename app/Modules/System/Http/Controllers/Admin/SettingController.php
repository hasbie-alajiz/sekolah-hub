<?php

declare(strict_types=1);

namespace App\Modules\System\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\System\Http\Requests\UpdateSettingsRequest;
use App\Modules\System\Models\Setting;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', Setting::class);

        $settings = Setting::all();

        return view('system::admin.settings.index', compact('settings'));
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        Gate::authorize('update', Setting::class);

        $validated = $request->validated();
        $oldSettings = Setting::all()->pluck('value', 'key')->toArray();

        foreach ($validated['settings'] as $key => $value) {
            $this->systemService->setSetting($key, $value);
        }

        // Log audit
        $newSettings = Setting::all()->pluck('value', 'key')->toArray();
        $this->systemService->logAudit('settings.update', null, $oldSettings, $newSettings);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
