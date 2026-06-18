<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultStoreRequest;
use App\Models\Result;
use App\Models\Test;
use App\Services\ExcelExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResultController extends Controller
{
    const PAGINATE_PER_PAGE = 15;
    public function index(Request $request): View
    {
        $query = Result::with(['user', 'test.topic', 'test.question_level']);

        if ($request->filled('user_search')) {
            $search = $request->user_search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('test_id')) {
            $query->where('test_id', $request->test_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if (auth()->user()->role === 'admin' || auth()->user()->role === 'teacher') {
            $results = $query->paginate(self::PAGINATE_PER_PAGE)->withQueryString();
        } else {
            $results = $query->where('user_id', auth()->id())
                ->paginate(self::PAGINATE_PER_PAGE)
                ->withQueryString();
        }

        $tests = Test::with('topic')->get();

        return view('results.index', [
            'results' => $results,
            'tests' => $tests,
            'filters' => $request->only(['user_search', 'test_id', 'start_date', 'end_date']),
        ]);
    }

    public function myResults(): View
    {
        $results = Result::where('user_id', auth()->id())
            ->with(['test'])
            ->paginate(self::PAGINATE_PER_PAGE);

        return view('results.my', ['results' => $results]);
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

    public function forceDestroy($id): RedirectResponse
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

    public function exportExcel(Request $request, ExcelExportService $excelService)
    {
        $testId = $request->get('test_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Result::with(['user', 'test.topic', 'test.question_level']);

        if ($testId) {
            $query->where('test_id', $testId);
        }
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $results = $query->get();

        $filename = 'results_' . date('Y-m-d_H-i') . '.xlsx';

        return $excelService->exportResults($results, $filename);
    }

    public function exportDetail(Result $result, ExcelExportService $excelService)
    {
        $details = [];

        if ($result->answers_file_path && Storage::exists($result->answers_file_path)) {
            $details = json_decode(Storage::get($result->answers_file_path), true);
        }

        $filename = 'result_detail_' . $result->id . '_' . date('Y-m-d_H-i') . '.xlsx';

        return $excelService->exportResultDetail($result, $details, $filename);
    }

}
