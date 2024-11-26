<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\PdfFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class PDFviewController extends Controller
{
    public function createPdf(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10240',
        ]);

        $file = $request->file('pdf_file');

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file->getPathname());

        if ($mimeType !== 'application/pdf') {
            return back()->withErrors(['pdf_file' => 'Yüklenen dosya geçerli bir PDF değil.']);
        }

        $fileContent = file_get_contents($file->getPathname());
        if (strpos($fileContent, '%PDF') !== 0) {
            return back()->withErrors(['pdf_file' => 'Geçerli bir PDF dosyası değil.']);
        }

        $hashedFileName = (string) \Illuminate\Support\Str::uuid() . '.pdf';
        $pdfFilePath = storage_path('app/pdfs/' . $hashedFileName);

        $directory = dirname($pdfFilePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $file->move($directory, $hashedFileName);

        $user = Auth::user();
        if ($user) {
            $pdfFile = new PdfFile();
            $pdfFile->unikey = uniqid();
            $pdfFile->user_id = $user->id;
            $pdfFile->file_path = basename($pdfFilePath);
            $pdfFile->file_name = $file->getClientOriginalName();
            $pdfFile->save();
        }

        return redirect()->route('panel.index')->with('status', 'PDF başarıyla yüklendi ve kaydedildi.');
    }

    public function showPdf($id)
    {
        $user = Auth::user();

        if (RateLimiter::tooManyAttempts($user->id, 10)) {
            return response('Çok fazla deneme', 429);
        }
        RateLimiter::hit($user->id, 10);

        $pdfFile = PdfFile::where('unikey', $id)->first();

        if (!$pdfFile) {
            return redirect('/')->withErrors(['error' => 'PDF dosyası bulunamadı.']);
        }

        if ($pdfFile->user_id !== $user->id) {
            return redirect('/')->withErrors(['error' => 'Yetkisiz erişim.']);
        }

        $pdfFilePath = storage_path('app/pdfs/' . $pdfFile->file_path);

        if (!file_exists($pdfFilePath)) {
            return redirect('/')->withErrors(['error' => 'PDF dosyası bulunamadı.']);
        }

        if (file_exists($pdfFilePath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $pdfFile->file_name . '"');
            header('Content-Length: ' . filesize($pdfFilePath));
            readfile($pdfFilePath);
            exit;
        } else {
            return redirect()->back()->with('error', 'PDF dosyası görüntülenirken bir hata oluştu.');
        }

    }

    public function deletePdf(Request $request)
    {
        $pdfId = $request->input('id');
        $pdf =PdfFile::where('unikey', $pdfId)->first();

        $user = Auth::user();

        if (RateLimiter::tooManyAttempts($user->id, 10)) {
            return response('Çok fazla deneme', 429);
        }
        RateLimiter::hit($user->id, 10);

        if (!$pdf || $pdf->user_id !== $user->id) {
            return redirect('/')->withErrors(['error' => 'Yetkisiz erişim veya PDF kaydı bulunamadı.']);
        }

        $filePath = storage_path('app/pdfs/' . $pdf->file_path);

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                $pdf->delete();
                return redirect()->back()->with('status', 'PDF başarıyla silindi.');
            } else {
                return redirect()->back()->with('error', 'PDF dosyası silinirken bir hata oluştu.');
            }
        } else {
            return redirect()->back()->with('status', 'PDF dosyası bulunamadı.');
        }
    }

    public function downloadPdf($id)
    {
        $user = Auth::user();

        if (RateLimiter::tooManyAttempts($user->id, 10)) {
            return response('Çok fazla deneme', 429);
        }
        RateLimiter::hit($user->id, 10);

        $pdfFile = PdfFile::where('unikey', $id)->first();

        if (!$pdfFile || $pdfFile->user_id !== $user->id) {
            return redirect('/')->withErrors(['error' => 'Yetkisiz erişim veya PDF dosyası bulunamadı.']);
        }

        $pdfFilePath = storage_path('app/pdfs/' . $pdfFile->file_path);

        if (!file_exists($pdfFilePath)) {
            return redirect('/')->withErrors(['error' => 'PDF dosyası bulunamadı.']);
        }

        return response()->download($pdfFilePath, $pdfFile->file_name, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
