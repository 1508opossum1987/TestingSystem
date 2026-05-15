<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultStoreRequest;
use App\Models\Result;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(): View
    {
        $results = Result::query()
            ->orderBy('created_at')
            ->with(['user', 'test'])
            ->get();

        return view('results.index', [
            'results' => $results
        ]);
    }

    public function create(): View
    {
        return view('results.create');
    }

    public function store(ResultStoreRequest $resultStoreRequest): RedirectResponse
    {
        $validated = $resultStoreRequest->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $result = Result::query()->create($validated);

        return redirect()
            ->route('results.index')
            ->with('success', "Результат успешно создан!");
    }

    public function show(Result $result): View
    {
        return view('results.show', ['result' => $result]);
    }

    /*public function update(ResultStoreRequest $request, Result $result): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['name'] !== $result->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        try {
            $topic->name = $validated['name'];
        } catch (\Exception $exception) {
            abort(500, $exception->getMessage());
        }

        return redirect()
            ->route('topics.index')
            ->with('success', "Тема '{$topic->name}' успешно обновлена!");
    }*/

    public function destroy(Result $result): RedirectResponse
    {
        $resultId = $result->id;

        if ($result->test()->exists()) {
            return redirect()
                ->route('results.index')
                ->with('error', "Нельзя удалить результат '{$resultId}', так как он принадлежит тесту!");
        }

        $result->delete();

        return redirect()
            ->route('results.index')
            ->with('success', "Результат '{$resultId}' успешно удален!");
    }

    public function restore($id): RedirectResponse
    {
        $result = Result::withTrashed()
            ->findOrFail($id);

        $resultId = $result->id;

        if ($result->trashed()) {
            $result->restore();
            return redirect()
                ->route('results.index')
                ->with('success', "Результат '{$resultId}' успешно восстановлен!");
        }

        return redirect()
            ->route('results.index')
            ->with('success', "Результат '{$resultId}' не удалялся!");
    }

    public function forceDetroy($id): RedirectResponse
    {
        $result = Result::withTrashed()
            ->findOrFail($id);

        $resultId = $result->id;

        if ($result->trashed()) {
            $result->forceDelete();
            return redirect()
                ->route('results.index')
                ->with('success', "Результат '{$resultId}' успешно удален из корзины!");
        }

        return redirect()
            ->route('results.index')
            ->with('success', "Результат '{$resultId}' не находится в корзине!");
    }

    public function trashed(): View
    {
        $results = Result::onlyTrashed()->orderBy('id')->get();
        return view('results.trashed', ['results' => $results]);
    }
}
