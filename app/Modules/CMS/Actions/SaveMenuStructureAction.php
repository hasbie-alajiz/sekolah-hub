<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Menu;
use App\Modules\CMS\Models\MenuItem;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class SaveMenuStructureAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    /**
     * Save/synchronize menu structure.
     *
     * @param Menu $menu
     * @param array $items Array of menu items
     * @return void
     */
    public function execute(Menu $menu, array $items): void
    {
        DB::transaction(function () use ($menu, $items) {
            $oldValues = [
                'name' => $menu->name,
                'items_count' => $menu->items()->count(),
            ];

            // 1. Keep track of IDs we keep, delete the rest
            $keepIds = [];

            // Helper to recursively save items
            $saveItemRecursive = function (array $itemData, ?int $parentId = null) use (&$saveItemRecursive, $menu, &$keepIds) {
                $itemId = !empty($itemData['id']) ? (int) $itemData['id'] : null;

                $data = [
                    'menu_id' => $menu->id,
                    'parent_id' => $parentId,
                    'title' => $itemData['title'],
                    'type' => $itemData['type'] ?? 'custom',
                    'reference_type' => $itemData['reference_type'] ?? null,
                    'reference_id' => !empty($itemData['reference_id']) ? (int) $itemData['reference_id'] : null,
                    'url' => $itemData['url'] ?? null,
                    'target' => $itemData['target'] ?? '_self',
                    'sort_order' => (int) ($itemData['sort_order'] ?? 0),
                ];

                if ($itemId) {
                    $menuItem = MenuItem::where('menu_id', $menu->id)->findOrFail($itemId);
                    $menuItem->update($data);
                } else {
                    $menuItem = MenuItem::create($data);
                }

                $keepIds[] = $menuItem->id;

                if (!empty($itemData['children']) && is_array($itemData['children'])) {
                    foreach ($itemData['children'] as $childData) {
                        $saveItemRecursive($childData, $menuItem->id);
                    }
                }
            };

            foreach ($items as $itemData) {
                $saveItemRecursive($itemData, null);
            }

            // Delete removed items
            MenuItem::where('menu_id', $menu->id)->whereNotIn('id', $keepIds)->delete();

            // Log Audit
            $this->systemService->logAudit('cms.menu.save_structure', $menu, $oldValues, [
                'name' => $menu->name,
                'items_count' => count($keepIds),
            ]);
        });
    }
}
