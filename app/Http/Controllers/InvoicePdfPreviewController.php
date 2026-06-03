<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\GenerateInvoicePdfService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoicePdfPreviewController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice, GenerateInvoicePdfService $service): Response
    {
        $user = $request->user();

        abort_unless(
            $user && (
                $user->can('view-any invoice') ||
                $user->can('view invoice') ||
                $user->can('viewAny invoice')
            ),
            403,
        );

        return $service->preview($invoice);
    }
}
