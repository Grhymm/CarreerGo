<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CvController extends Controller
{
    public function download(Request $request): Response
    {
        $user = $request->user();
        $user->load('skills');

        $experiences = $user->experiences()->orderByDesc('start_date')->get();

        $pdf = Pdf::loadView('pdf.cv', [
            'user' => $user,
            'skills' => $user->skills,
            'experiences' => $experiences,
        ]);

        $filename = 'CV_'.str_replace(' ', '_', $user->name).'.pdf';

        return $pdf->download($filename);
    }
}
