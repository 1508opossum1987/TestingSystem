@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $test->topic->name }} ({{ $test->question_level->question_level }} класс)</h1>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <strong>Прогресс:</strong> {{ $answeredCount }} из {{ $totalCount }} вопросов отвечено
            </div>
            <div>
                <strong>Вопрос:</strong> {{ $currentIndex + 1 }} из {{ $totalCount }}
            </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            @foreach($questionsStatus as $status)
                <a href="{{ route('tests.answer', ['test' => $test, 'index' => $status['index']]) }}"
                   style="
                   display: inline-block;
                   width: 35px;
                   height: 35px;
                   text-align: center;
                   line-height: 35px;
                   border-radius: 50%;
                   text-decoration: none;
                   color: #333;
                   font-weight: bold;
                   background-color: {{ $status['is_current'] ? '#007bff' : ($status['is_answered'] ? '#28a745' : '#e9ecef') }};
                   color: {{ $status['is_current'] ? '#fff' : ($status['is_answered'] ? '#fff' : '#333') }};
                   border: 2px solid {{ $status['is_current'] ? '#007bff' : 'transparent' }};
               "
                   title="Вопрос {{ $status['index'] + 1 }} {{ $status['is_answered'] ? '(отвечен)' : '(не отвечен)' }}">
                    {{ $status['index'] + 1 }}
                </a>
            @endforeach
        </div>

        <div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
            <h3>Вопрос {{ $currentIndex + 1 }}:</h3>
            <p style="font-size: 18px; margin-bottom: 20px;">{{ $currentQuestion->question_text }}</p>

            <form action="{{ route('tests.saveAnswer', $test) }}" method="POST">
                @csrf
                <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                <input type="hidden" name="current_index" value="{{ $currentIndex }}">

                <div style="margin: 15px 0;">
                    @foreach($options as $key => $value)
                        <div style="margin: 8px 0; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px; background-color: {{ $currentAnswer == $key ? '#d4edda' : 'transparent' }};">
                            <label style="display: block; cursor: pointer; width: 100%;">
                                <input type="radio" name="answer" value="{{ $key }}"
                                       {{ $currentAnswer == $key ? 'checked' : '' }} required>
                                <strong>{{ $key }}.</strong> {{ $value }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button type="submit" style="background-color: #28a745; color: white; padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer;">
                        {{ $currentAnswer ? 'Изменить ответ' : 'Сохранить ответ' }}
                    </button>
                </div>
            </form>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                @if($currentIndex > 0)
                    <form action="{{ route('tests.navigate', $test) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="direction" value="prev">
                        <input type="hidden" name="current_index" value="{{ $currentIndex }}">
                        <button type="submit" style="padding: 8px 20px; border: 1px solid #007bff; background: transparent; border-radius: 4px; cursor: pointer;">
                            ← Назад
                        </button>
                    </form>
                @endif
            </div>

            <div>
                @if($currentIndex < $totalCount - 1)
                    <form action="{{ route('tests.navigate', $test) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="direction" value="next">
                        <input type="hidden" name="current_index" value="{{ $currentIndex }}">
                        <button type="submit" style="padding: 8px 20px; border: 1px solid #007bff; background: transparent; border-radius: 4px; cursor: pointer;">
                            Вперёд →
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            @if($allAnswered)
                <form action="{{ route('tests.complete', $test) }}" method="POST">
                    @csrf
                    <button type="submit" style="background-color: #28a745; color: white; padding: 12px 40px; border: none; border-radius: 4px; font-size: 18px; cursor: pointer;">
                        ✅ Завершить тест
                    </button>
                </form>
            @else
                <div style="color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 4px;">
                    ⚠️ Вы ответили не на все вопросы. Завершить тест можно только после ответа на все вопросы.
                </div>
            @endif
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('tests.show', $test) }}" style="color: #6c757d;">Отменить и вернуться</a>
        </div>
    </div>
@endsection
