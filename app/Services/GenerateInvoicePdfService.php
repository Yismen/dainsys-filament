<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GenerateInvoicePdfService
{
    public function download(Invoice $invoice): StreamedResponse
    {
        $content = $this->buildPdf($invoice)->output();

        return response()->streamDownload(function () use ($content): void {
            echo $content;
        }, $this->fileName($invoice), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function preview(Invoice $invoice): Response
    {
        $content = $this->buildPdf($invoice)->output();

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$this->fileName($invoice).'"',
        ]);
    }

    protected function buildPdf(Invoice $invoice): \Barryvdh\DomPDF\PDF
    {
        $invoice->loadMissing(['project.client', 'campaign']);

        $lineItems = $this->resolveLineItems($invoice);
        $calculatedTotal = $lineItems->sum('amount');

        return Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'lineItems' => $lineItems,
            'totalAmount' => $calculatedTotal > 0 ? $calculatedTotal : (float) ($invoice->total_amount ?? 0),
        ])->setPaper('a4');
    }

    protected function fileName(Invoice $invoice): string
    {
        $invoiceNumber = $invoice->number ?: $invoice->getKey();

        return "invoice-{$invoiceNumber}.pdf";
    }

    /**
     * @return Collection<int, array{description: string, qty: float, unit_price: float, amount: float}>
     */
    protected function resolveLineItems(Invoice $invoice): Collection
    {
        if (! is_array($invoice->items) || $invoice->items === []) {
            return collect();
        }

        $itemNamesById = Item::query()
            ->whereIn('id', collect($invoice->items)->pluck('item_id')->filter()->all())
            ->pluck('name', 'id');

        return collect($invoice->items)
            ->filter(fn (mixed $row): bool => is_array($row))
            ->map(function (array $row) use ($itemNamesById): array {
                $qty = (float) ($row['qty'] ?? $row['quantity'] ?? 1);
                $unitPrice = (float) ($row['unit_price'] ?? $row['price'] ?? 0);
                $amount = (float) ($row['amount'] ?? ($qty * $unitPrice));

                return [
                    'description' => (string) ($row['description'] ?? $row['name'] ?? $itemNamesById->get($row['item_id'] ?? '') ?? 'Item'),
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                ];
            })
            ->values();
    }
}
