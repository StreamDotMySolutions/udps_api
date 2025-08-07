<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

// PhpWord and PhpSpreadsheet
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIOFactory;

class DocumentClassificationController extends Controller
{
    public function classify(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
        ], [
            'file.required' => 'You must upload a document.',
            'file.mimes' => 'Only JPG, PNG, PDF, Word, or Excel files are allowed.',
            'file.max' => 'The file size must not exceed 10MB.',
        ]);

        // Store file temporarily
        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(20) . '.' . $ext;
        $path = $file->storeAs('uploads', $filename);
        $fullPath = storage_path("app/$path");

        // Extract text from file
        $text = $this->extractText($fullPath, $ext);

        if (empty(trim($text))) {
            return response()->json([
                'error' => 'Unable to extract text from document.',
            ], 422);
        }

        // Send to OpenAI to classify
        $documentType = $this->classifyWithAI($text);

        return response()->json([
            'type' => $documentType,
        ]);
    }

    private function extractText(string $path, string $ext): string
    {
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            return shell_exec("tesseract " . escapeshellarg($path) . " stdout 2>/dev/null");
        }

        if ($ext === 'pdf') {
            return shell_exec("pdftotext " . escapeshellarg($path) . " -");
        }

        if (in_array($ext, ['doc', 'docx'])) {
            return $this->extractFromDoc($path);
        }

        if (in_array($ext, ['xls', 'xlsx'])) {
            return $this->extractFromExcel($path);
        }

        return '';
    }

    private function extractFromDoc(string $path): string
    {
        $phpWord = WordIOFactory::load($path);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }

        return $text;
    }

    private function extractFromExcel(string $path): string
    {
        $spreadsheet = ExcelIOFactory::load($path);
        $text = '';

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            foreach ($sheet->toArray() as $row) {
                $text .= implode(' ', $row) . "\n";
            }
        }

        return $text;
    }

    private function classifyWithAI(string $text): string
    {
        $prompt = <<<EOT
Classify the following document as one of these types:
- invoice
- receipt
- quotation
- purchase order
- unknown

Return only one word: invoice, receipt, quotation, purchase order, or unknown.

Text:
$text

Type:
EOT;

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0,
        ]);

        return trim($response->choices[0]->message->content);
    }
}
