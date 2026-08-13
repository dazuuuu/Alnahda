<?php

namespace App\Services;

use App\Models\Application;

/**
 * Excel (SpreadsheetML .xls) and PDF downloads of the applicant-facing
 * application fields. Used by the admin applications list and detail pages.
 */
class ApplicationExportService
{
    /**
     * @param array<int,array<string,mixed>> $applications
     * @param array<string,string>|null $labels
     */
    public static function downloadExcel(array $applications, string $filename, ?array $labels = null, string $sheetTitle = 'Applications'): void
    {
        $body = self::excelXml($applications, $labels ?? Application::PUBLIC_FIELDS, $sheetTitle);
        self::sendDownload($filename . '.xls', 'application/vnd.ms-excel; charset=UTF-8', $body);
    }

    /**
     * @param array<int,array<string,mixed>> $applications
     * @param array<string,string>|null $labels
     */
    public static function downloadPdf(array $applications, string $filename, ?array $labels = null, string $heading = 'Al NAHDA Agency - Applications'): void
    {
        $body = self::pdfBinary($applications, $labels ?? Application::PUBLIC_FIELDS, $heading);
        self::sendDownload($filename . '.pdf', 'application/pdf', $body);
    }

    private static function sendDownload(string $filename, string $contentType, string $body): void
    {
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($body));
        echo $body;
        exit;
    }

    /**
     * @param array<int,array<string,mixed>> $applications
     * @param array<string,string> $labels
     */
    private static function excelXml(array $applications, array $labels, string $sheetTitle): string
    {
        $sheetTitle = self::excelSheetName($sheetTitle);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
        $xml .= '<Styles>';
        $xml .= '<Style ss:ID="header"><Font ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="#132C5C" ss:Pattern="Solid"/></Style>';
        $xml .= '</Styles>' . "\n";
        $xml .= '<Worksheet ss:Name="' . self::xml($sheetTitle) . '"><Table>' . "\n";

        $xml .= '<Row>';
        foreach ($labels as $label) {
            $xml .= '<Cell ss:StyleID="header"><Data ss:Type="String">' . self::xml($label) . '</Data></Cell>';
        }
        $xml .= "</Row>\n";

        foreach ($applications as $application) {
            $xml .= '<Row>';
            foreach (array_keys($labels) as $field) {
                $xml .= '<Cell><Data ss:Type="String">' . self::xml(Application::formatPublicValue($application, $field)) . '</Data></Cell>';
            }
            $xml .= "</Row>\n";
        }

        $xml .= '</Table></Worksheet></Workbook>';
        return $xml;
    }

    /**
     * @param array<int,array<string,mixed>> $applications
     * @param array<string,string> $labels
     */
    private static function pdfBinary(array $applications, array $labels, string $heading): string
    {
        $pageW = 842; // A4 landscape
        $pageH = 595;
        $margin = 36;
        $fontSize = 8;
        $headerSize = 11;
        $lineH = 14;
        $usableW = $pageW - ($margin * 2);

        $colKeys = array_keys($labels);
        $colWeights = [
            'fullname' => 1.4,
            'age' => 0.5,
            'validPassport' => 1.0,
            'phone' => 1.1,
            'county' => 1.0,
            'travelledSaudia' => 1.6,
            'appointmentPreference' => 1.1,
            'status' => 1.0,
            'submitted_at' => 0.9,
        ];
        $weightSum = 0.0;
        foreach ($colKeys as $key) {
            $weightSum += $colWeights[$key] ?? 1.0;
        }
        $colWidths = [];
        foreach ($colKeys as $key) {
            $colWidths[$key] = $usableW * (($colWeights[$key] ?? 1.0) / $weightSum);
        }

        $pages = [];
        $content = '';
        $y = $pageH - $margin;

        $drawHeader = function () use (&$content, &$y, $labels, $colKeys, $colWidths, $margin, $pageH, $headerSize, $lineH, $heading) {
            $content .= self::pdfText($margin, $y, $heading, $headerSize, true);
            $y -= ($lineH + 6);
            $x = $margin;
            foreach ($colKeys as $key) {
                $content .= self::pdfText($x + 2, $y, (string) $labels[$key], 8, true);
                $x += $colWidths[$key];
            }
            $y -= 4;
            $content .= sprintf("0.08 0.16 0.32 RG %.2F %.2F m %.2F %.2F l S\n", $margin, $y, $margin + array_sum($colWidths), $y);
            $y -= $lineH;
        };

        $newPage = function () use (&$pages, &$content, &$y, $pageH, $margin, $drawHeader) {
            if ($content !== '') {
                $pages[] = $content;
            }
            $content = '';
            $y = $pageH - $margin;
            $drawHeader();
        };

        $newPage();

        foreach ($applications as $application) {
            if ($y < $margin + $lineH) {
                $newPage();
            }
            $x = $margin;
            foreach ($colKeys as $key) {
                $value = Application::formatPublicValue($application, $key);
                $maxChars = max(4, (int) floor($colWidths[$key] / 5.2));
                $content .= self::pdfText($x + 2, $y, self::clip($value, $maxChars), $fontSize, false);
                $x += $colWidths[$key];
            }
            $y -= $lineH;
        }

        if ($content !== '') {
            $pages[] = $content;
        }
        if (!$pages) {
            $pages[] = self::pdfText($margin, $pageH - $margin, 'No applications to export.', 12, false);
        }

        return self::assemblePdf($pages, $pageW, $pageH);
    }

    private static function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private static function excelSheetName(string $name): string
    {
        $name = preg_replace('/[\\\\\/\?\*\[\]:]/', ' ', $name) ?? $name;
        $name = trim($name);
        if ($name === '') {
            return 'Applications';
        }
        return function_exists('mb_substr') ? mb_substr($name, 0, 31) : substr($name, 0, 31);
    }

    private static function clip(string $value, int $maxChars): string
    {
        $len = function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
        if ($len <= $maxChars) {
            return $value;
        }
        $cut = function_exists('mb_substr')
            ? mb_substr($value, 0, max(1, $maxChars - 1))
            : substr($value, 0, max(1, $maxChars - 1));
        return rtrim($cut) . '...';
    }

    private static function pdfCoord(float $n): string
    {
        return number_format($n, 2, '.', '');
    }

    private static function pdfText(float $x, float $y, string $text, int $size, bool $bold): string
    {
        $font = $bold ? '/F2' : '/F1';
        return 'BT ' . $font . ' ' . $size . ' Tf ' . self::pdfCoord($x) . ' ' . self::pdfCoord($y) . ' Td (' . self::pdfEscape($text) . ") Tj ET\n";
    }

    private static function pdfEscape(string $text): string
    {
        $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT', $text);
        if ($converted === false) {
            $converted = preg_replace('/[^\x20-\x7E]/', '?', $text) ?? $text;
        }
        return str_replace(['\\', '(', ')', "\r", "\n"], ['\\\\', '\\(', '\\)', '', ' '], $converted);
    }

    /**
     * @param list<string> $pageStreams
     */
    private static function assemblePdf(array $pageStreams, int $pageW, int $pageH): string
    {
        $objects = [];
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';

        $fontRegular = 3 + (2 * count($pageStreams));
        $fontBold = $fontRegular + 1;

        $kids = [];
        $contentIds = [];
        $pageCount = count($pageStreams);
        for ($i = 0; $i < $pageCount; $i++) {
            $pageId = 3 + $i;
            $contentId = 3 + $pageCount + $i;
            $kids[] = $pageId . ' 0 R';
            $contentIds[$pageId] = $contentId;
        }

        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . $pageCount . ' >>';

        foreach ($kids as $index => $unused) {
            $pageId = 3 + $index;
            $contentId = $contentIds[$pageId];
            $objects[$pageId] = sprintf(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %d %d] /Resources << /Font << /F1 %d 0 R /F2 %d 0 R >> >> /Contents %d 0 R >>',
                $pageW,
                $pageH,
                $fontRegular,
                $fontBold,
                $contentId
            );
            $stream = $pageStreams[$index];
            $objects[$contentId] = '<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . 'endstream';
        }

        $objects[$fontRegular] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
        $objects[$fontBold] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= $id . " 0 obj\n" . $body . "\nendobj\n";
        }

        $maxId = max(array_keys($objects));
        $xrefPos = strlen($pdf);
        $pdf .= 'xref' . "\n0 " . ($maxId + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($id = 1; $id <= $maxId; $id++) {
            $pdf .= sprintf('%010d 00000 n ' . "\n", $offsets[$id] ?? 0);
        }
        $pdf .= 'trailer << /Size ' . ($maxId + 1) . ' /Root 1 0 R >>' . "\n";
        $pdf .= 'startxref' . "\n" . $xrefPos . "\n%%EOF";

        return $pdf;
    }
}
