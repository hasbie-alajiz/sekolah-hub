<?php

declare(strict_types=1);

namespace App\Modules\CMS\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\CMS\Models\Category;
use App\Modules\CMS\Http\Requests\StoreCategoryRequest;
use App\Modules\CMS\Http\Requests\UpdateCategoryRequest;
use App\Modules\CMS\Actions\CreateCategoryAction;
use App\Modules\CMS\Actions\UpdateCategoryAction;
use App\Modules\CMS\Actions\DeleteCategoryAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private CreateCategoryAction $createCategoryAction,
        private UpdateCategoryAction $updateCategoryAction,
        private DeleteCategoryAction $deleteCategoryAction
    ) {
    }

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Category::class);

        $query = Category::with('parent');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        return view('cms::admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        Gate::authorize('create', Category::class);

        $parentCategories = Category::all();

        return view('cms::admin.categories.create', compact('parentCategories'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->createCategoryAction->execute($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil disimpan.');
    }

    public function edit(Category $category): View
    {
        Gate::authorize('update', $category);

        $parentCategories = Category::where('id', '!=', $category->id)->get();

        return view('cms::admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);

        $this->updateCategoryAction->execute($category, $request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        $this->deleteCategoryAction->execute($category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
