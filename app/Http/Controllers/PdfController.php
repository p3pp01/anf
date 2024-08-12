<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use FPDF;
use App\Models\PdfImportLog;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function importPdf(Request $request)
    {

        try {
            // Percorso del PDF che vuoi importare
            $filePath = storage_path('/app/public/pdf_files/import-pdf/20240724provinciabiella.pdf');

            $filename = basename($filePath);

            $importedAt = Carbon::now();

            // Creazione di una nuova istanza di FPDI
            $pdf = new Fpdi();

            // Numero di pagine nel PDF
            $pageCount = $pdf->setSourceFile($filePath);

            // Itera attraverso tutte le pagine del PDF
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // Importa la pagina specifica
                $templateId = $pdf->importPage($pageNo);

                // Ottieni le dimensioni del template (pagina originale)
                $size = $pdf->getTemplateSize($templateId);

                // Aggiungi una nuova pagina con la stessa dimensione dell'originale
                $pdf->addPage($size['orientation'], [$size['width'], $size['height']]);

                // Usa il template (pagina del PDF originale)
                $pdf->useTemplate($templateId);
            }

            // Puoi salvare il nuovo PDF, visualizzarlo o lavorarci ulteriormente
            $outputPath = storage_path('/app/public/pdf_files/processed_pdfs/' . $filename);
            $pdf->Output('I', $outputPath);

            // Log successo nel database
            PdfImportLog::create([
                'file_name' => $filename,
                'imported_at' => $importedAt,
                'successful' => true,
            ]);

        } catch (CrossReferenceException $e) {
            $this->logError($fileName, $importedAt, $e->getMessage());
        } catch (PdfParserException $e) {
            $this->logError($fileName, $importedAt, $e->getMessage());
        } catch (PdfTypeException $e) {
            $this->logError($fileName, $importedAt, $e->getMessage());
        } catch (\Exception $e) {
            $this->logError($fileName, $importedAt, $e->getMessage());
        }
    }

    public function importMultiplePdfs(Request $request)
    {
        // Percorso della directory che contiene i file PDF
        $directoryPath = storage_path('/app/public/pdf_files/import-pdf/');

        // Ottieni tutti i file PDF nella directory
        $pdfFiles = glob($directoryPath . '*.pdf');

        // Itera su ciascun file PDF
        foreach ($pdfFiles as $filePath) {
            $fileName = basename($filePath);
            $importedAt = Carbon::now();

            try {
                // Crea un'istanza di FPDI
                $pdf = new Fpdi();

                // Numero di pagine nel PDF
                $pageCount = $pdf->setSourceFile($filePath);

                // Itera attraverso tutte le pagine del PDF
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    // Importa la pagina specifica
                    $templateId = $pdf->importPage($pageNo);

                    // Ottieni le dimensioni del template (pagina originale)
                    $size = $pdf->getTemplateSize($templateId);

                    // Aggiungi una nuova pagina con la stessa dimensione dell'originale
                    $pdf->addPage($size['orientation'], [$size['width'], $size['height']]);

                    // Usa il template (pagina del PDF originale)
                    $pdf->useTemplate($templateId);
                }

                // Salva il nuovo PDF o esegui altre operazioni
                $outputPath = storage_path('/app/public/pdf_files/processed_pdfs/' . $fileName);
                $pdf->Output('F', $outputPath);

                // Log successo nel database
                PdfImportLog::create([
                    'file_name' => $fileName,
                    'imported_at' => $importedAt,
                    'successful' => true,
                ]);

            } catch (CrossReferenceException $e) {
                $this->logError($fileName, $importedAt, $e->getMessage());
            } catch (PdfParserException $e) {
                $this->logError($fileName, $importedAt, $e->getMessage());
            } catch (PdfTypeException $e) {
                $this->logError($fileName, $importedAt, $e->getMessage());
            } catch (\Exception $e) {
                $this->logError($fileName, $importedAt, $e->getMessage());
            }
        }

        return response()->json(['message' => 'All PDFs has been processed, see the logs for more information.'], 200);
    }

    protected function logError($fileName, $importedAt, $errorMessage)
    {
        // Log errore nel database
        PdfImportLog::create([
            'file_name' => $fileName,
            'imported_at' => $importedAt,
            'successful' => false,
            'error_message' => $errorMessage,
        ]);
    }
}
