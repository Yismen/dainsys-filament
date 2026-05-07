<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 90px;
            font-family: Arial, sans-serif;
            color: #333;
            background: #ffffff;
        }

        .page {
            width: 100%;
            padding: 0;
        }

        .invoice {
            width: 100%;
        }

        .invoice-header {
            margin-bottom: 20px;
        }

        .invoice-meta {
            margin-top: 12px;
            margin-left: 10px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .invoice-meta-content {
            padding: 8px;
            display: inline-block;
            text-align: left;
        }

        .brand {
            flex: 1 1 0;
            display: flex;
            align-items: start;
        }

        .title-block {
            /* width: 30%; */
            text-align: right;
        }

        .brand-inner {
            display: flex;
            align-items: flex-start;
        }

        .brand-logo {
            width: 80px;
            flex-shrink: 0;
        }

        .brand-logo img {
            width: 80px;
            height: auto;
            display: block;
            margin: 0;
            padding: 0;
            margin-bottom: 10px;
        }

        .brand-company {
            flex: 1 1 0;
            vertical-align: top;
            margin-top: 0;
        }

        .company-name {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #333;
            line-height: 1.1;
        }

        .company-address {
            margin-top: 4px;
            white-space: pre-line;
            color: #3f3f3f;
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .title {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1;
            color: #35373a;
        }

        .status-text {
            margin-top: 6px;
            font-size: 1.2em;
            font-weight: 700;
            color: {{ $invoice->status?->getTextColor() }};
            text-transform: uppercase;
            line-height: 1.2;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .section-gap {
            height: 16px;
        }

        .billing {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .billing td {
            vertical-align: top;
            padding: 0;
        }

        .billing p,
        .info p,
        .dates p {
            margin: 4px 0;
        }

        .bill-to {
            width: 50%;
            padding-right: 8px;
        }

        .bill-to-label,
        .box-header {
            display: inline-block;
            background-color: rgb(191, 115, 0);
            color: #fff;
            padding: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            margin: 0;
            width: 60%;
        }

        .bill-to-content {
            color: #3f3f3f;
            font-size: 0.95rem;
            margin: 0;
            padding: 10px 0;
        }

        .bill-to-client {
            font-weight: 700;
            margin: 0;
            padding: 0;
        }

        .meta {
            width: 50%;
            padding-top: 0;
        }

        .meta-line {
            font-size: 0.95rem;
            color: #3f3f3f;
            /* line-height: 1.5; */
            overflow-wrap: anywhere;
            word-break: break-word;
            /* margin-bottom: 4px; */
        }

        .meta-label {
            font-weight: 700;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table.products th,
        table.products td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table.products td {
            text-align: right;
            font-size: 0.9rem;
        }

        table th {
            background-color: #f2f2f2;
        }

        table.products tr.table-header > th {
            font-weight: bold;
            text-transform: uppercase;
            background-color: rgb(191, 115, 0);
            color: white;
            margin: 0;
        }

        table td.description {
            text-align: left;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .items .description {
            text-align: left;
        }

        .items .qty,
        .items .unit,
        .items .amount {
            white-space: nowrap;
        }

        .total-row td {
            background: #f2f2f2;
            font-weight: bold;
            font-size: 0.95rem;
        }

        .total-label,
        .total-value {
            text-align: right;
        }

        .footer {
            font-size: 0.9em;
            color: #777;
            margin-top: 30px;
        }

        .terms {
            margin-top: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-blue {
            color: #007bff;
        }

        .text-red {
            color: #dc3545;
        }

        .cool-gray {
            color: #6c757d;
        }

        .muted {
            color: #6c757d;
        }
    </style>
</head>
<body>
@php
    $rawCompanyAddress = (string) config('app.company.address', "1840 NW 125TH TERRACE\nPEMBROKE PINES, FL 33028");
    $companyAddress = trim(str_replace('\\n', "\n", strip_tags($rawCompanyAddress)));

    $logoFilePath = public_path('images/ecco-logo.png');
    $logoPath = file_exists($logoFilePath) ? $logoFilePath : null;

    $client = $invoice->project?->client;
    $clientAttributes = $client?->getAttributes() ?? [];
    $invoiceAttributes = $invoice->getAttributes();

    $billContact = $clientAttributes['person_of_contact'] ?? null;
    $billClient = $client?->name ?? '-';
    $billAddress = collect([
        $clientAttributes['address'] ?? null,
        $clientAttributes['address_line_1'] ?? null,
        $clientAttributes['address_line_2'] ?? null,
        collect([
            $clientAttributes['city'] ?? null,
            $clientAttributes['state'] ?? null,
            $clientAttributes['zip'] ?? null,
        ])->filter(fn ($value): bool => filled($value))->implode(', '),
        $clientAttributes['country'] ?? null,
    ])->filter(fn ($value): bool => filled($value))->implode("\n");

    $invoiceDate = $invoice->date?->format('M d, Y') ?? '-';
    $fileSentAtRaw = $invoiceAttributes['file_sent_at'] ?? null;
    $fileSentAt = filled($fileSentAtRaw)
        ? \Illuminate\Support\Carbon::parse((string) $fileSentAtRaw)->format('M d, Y')
        : $invoiceDate;

    $publication = $invoiceAttributes['publication'] ?? $invoice->campaign?->name ?? '-';
    $statusText = str($invoice->status?->value ?? $invoice->status ?? 'pending')->replace('_', ' ')->upper()->toString();

    $money = static fn (float $value): string => '$' . number_format($value, 2, '.', ',');
    $qtyText = static function (float $value): string {
        if (fmod($value, 1.0) === 0.0) {
            return number_format($value, 0, '.', ',');
        }

        return number_format($value, 2, '.', ',');
    };

    $dueDate = $invoice->due_date?->format('M d, Y');
    $netDays = null;
    if ($invoice->date && $invoice->due_date) {
        $netDays = $invoice->date->diffInDays($invoice->due_date);
    }
    $netDays = $netDays ?? $invoice->project?->invoice_net_days ?? 30;

    $bankName = config('app.company.bank_name', 'TD Bank, NA. West Palm Beach, FL');
    $bankAccount = config('app.company.bank_account', '4443837187');
    $bankRouting = config('app.company.bank_routing', '067014822');
    $bankWire = config('app.company.bank_wire', '031101266');
@endphp

<div class="page">
    <div class="invoice">
        <table>
            <tr class="invoice-header">
                <td class="brand">
                    <div class="brand-inner">
                        <div class="brand-logo">
                            @if ($logoPath)
                                <img src="{{ $logoPath }}" alt="Ecco logo">
                            @endif
                        </div>
                        <div class="brand-company">
                            <div class="company-name">Ecco Outsourcing Group</div>
                            <div class="company-address">{{ $companyAddress }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="title-block">
                        <div class="title">INVOICE</div>
                        <div class="status-text">{{ $statusText }}</div>
                        <div class="invoice-meta">
                            <div class="invoice-meta-content">
                                <div class="meta-line"><span class="meta-label">Invoice #:</span> {{ $invoice->number ?? '-' }}</div>
                                <div class="meta-line"><span class="meta-label">Invoice Date:</span> {{ $invoiceDate }}</div>
                                <div class="meta-line"><span class="meta-label">File Sent At:</span> {{ $fileSentAt }}</div>
                                <div class="meta-line"><span class="meta-label">Publication:</span> {{ $publication }}</div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-gap"></div>

        <table class="billing">
            <tr>
                <td class="bill-to">
                    <div class="bill-to-label box-header">BILL TO:</div>
                    <div class="bill-to-content">
                        @if (filled($billContact))
                            {{ $billContact }}
                            <br>
                        @endif
                        <span class="bill-to-client">{{ $billClient }}</span>

                        @if ($billAddress !== '')
                            <br>
                            {!! nl2br(e($billAddress)) !!}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table class="items products">
            <thead>
                <tr class="table-header">
                    <th class="description">DESCRIPTION</th>
                    <th class="qty">QTY</th>
                    <th class="unit">UNIT PRICE</th>
                    <th class="amount">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lineItems as $lineItem)
                    <tr>
                        <td class="description">{{ $lineItem['description'] }}</td>
                        <td class="qty">{{ $qtyText((float) $lineItem['qty']) }}</td>
                        <td class="unit">{{ $money((float) $lineItem['unit_price']) }}</td>
                        <td class="amount">{{ $money((float) $lineItem['amount']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="description muted">No items</td>
                        <td class="qty">0</td>
                        <td class="unit">$0.00</td>
                        <td class="amount">$0.00</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td class="total-label" colspan="3">Total amount</td>
                    <td class="total-value">{{ $money((float) $totalAmount) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="footer">
            <tr>
                <td>
                    <div class="thank-you">Thank you for your business!</div>

                    <div class="terms">
                        <span class="label">Payment Terms:</span>
                        Net {{ $netDays }} Days
                        @if (filled($dueDate))
                            (By {{ $dueDate }})
                        @endif
                    </div>

                    <div class="wire"><span class="label">Wire Payment Info:</span>
{{ $bankName }}
Routing: {{ $bankRouting }} &ndash; Account: {{ $bankAccount }} &ndash; Wire: {{ $bankWire }}</div>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
