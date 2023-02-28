<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('api', function (Request $request) {
    $validated = $request->validate([
        'wordDocument' => 'required|file|mimes:doc,docx|max:10024',
//        'varName' => 'required',
//        'varValue' => 'required',
    ]);

    if (!$validated) {
        return null;
    }

    $file = $request->wordDocument;
    $varName = $request->varName;
    $varValue = $request->varValue;
    $path = $file->store('src');

    $wordDocument = storage_path("app/$path");
    $templateProcessor = new TemplateProcessor($wordDocument);
    //$variables = $templateProcessor->getVariables();
    //$variable = $variables[0];
    $templateProcessor->setValue("{$varName}", $varValue);
    $templateProcessor->saveAs($wordDocument);

    $domPdfPath = base_path('vendor/dompdf/dompdf');
    Settings::setPdfRendererPath($domPdfPath);
    Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
    $phpWord = IOFactory::load($wordDocument);
    $xmlWriter = IOFactory::createWriter($phpWord, 'PDF');

    $pdfDocument = public_path("$path.pdf");
    $xmlWriter->save($pdfDocument);

    return "http://word/$path.pdf";
});
