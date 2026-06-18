<?php

namespace App\Services;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    public function exportResults($results, string $filename): StreamedResponse
    {
        return response()->stream(function () use ($results, $filename) {
            $writer = new Writer();
            $writer->openToBrowser($filename);

            // Стиль для заголовков (жирный, фон серый)
            $headerStyle = (new Style())
                ->setFontBold()
                ->setBackgroundColor(Color::LIGHT_GRAY)
                ->setCellAlignment(CellAlignment::CENTER);

            // Стиль для данных (с границами)
            $dataStyle = (new Style())
                ->setCellAlignment(CellAlignment::LEFT)
                ->setBorder(
                    (new Border())
                        ->addPart(new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN))
                        ->addPart(new BorderPart(Border::LEFT, Color::BLACK, Border::WIDTH_THIN))
                        ->addPart(new BorderPart(Border::RIGHT, Color::BLACK, Border::WIDTH_THIN))
                );

            // Заголовки
            $headers = [
                'ID',
                'Пользователь',
                'Email',
                'Тест ID',
                'Тема',
                'Уровень',
                'Результат (%)',
                'Оценка',
                'Дата прохождения',
            ];

            $headerRow = Row::fromValues($headers, $headerStyle);
            $writer->addRow($headerRow);

            // Данные
            foreach ($results as $result) {
                $rowData = [
                    $result->id,
                    $result->user->name ?? '—',
                    $result->user->email ?? '—',
                    $result->test->id ?? '—',
                    $result->test->topic->name ?? '—',
                    $result->test->question_level->question_level ?? '—',
                    $result->score_percent,
                    $result->grade ?? '—',
                    $result->created_at->format('d.m.Y H:i'),
                ];

                $row = Row::fromValues($rowData, $dataStyle);
                $writer->addRow($row);
            }

            $writer->close();
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportResultDetail($result, $details, string $filename): StreamedResponse
    {
        return response()->stream(function () use ($result, $details, $filename) {
            $writer = new Writer();
            $writer->openToBrowser($filename);

            // Стиль для заголовков
            $headerStyle = (new Style())
                ->setFontBold()
                ->setBackgroundColor(Color::LIGHT_GRAY)
                ->setCellAlignment(CellAlignment::CENTER);

            $dataStyle = (new Style())
                ->setCellAlignment(CellAlignment::LEFT)
                ->setBorder(
                    (new Border())
                        ->addPart(new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN))
                        ->addPart(new BorderPart(Border::LEFT, Color::BLACK, Border::WIDTH_THIN))
                        ->addPart(new BorderPart(Border::RIGHT, Color::BLACK, Border::WIDTH_THIN))
                );

            // Заголовки деталей
            $detailHeaders = [
                'Вопрос',
                'Ваш ответ',
                'Правильный ответ',
                'Результат',
            ];

            $headerRow = Row::fromValues($detailHeaders, $headerStyle);
            $writer->addRow($headerRow);

            // Детали ответов
            if (!empty($details['answers'])) {
                foreach ($details['answers'] as $questionId => $answer) {
                    $rowData = [
                        $answer['question_text'] ?? 'Вопрос ' . $questionId,
                        $answer['user_answer'] ?? '—',
                        $answer['correct_answer'] ?? '—',
                        $answer['is_correct'] ? 'Правильно' : 'Неправильно',
                    ];

                    $row = Row::fromValues($rowData, $dataStyle);
                    $writer->addRow($row);
                }
            }

            // Пустая строка и итог
            $writer->addRow(Row::fromValues(['']));

            $summaryData = [
                ['Итоговый результат', $result->score_percent . '%'],
                ['Оценка', $result->grade ?? '—'],
            ];

            $summaryStyle = (new Style())->setFontBold();
            foreach ($summaryData as $item) {
                $row = Row::fromValues($item, $summaryStyle);
                $writer->addRow($row);
            }

            $writer->close();
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
