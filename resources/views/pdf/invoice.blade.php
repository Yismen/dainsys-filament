<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #3f3f3f;
            line-height: 1.22;
            background: #ffffff;
        }

        .page {
            width: 100%;
            padding: 0;
        }

        .invoice {
            width: 100%;
        }

        .header,
        .bill-meta,
        .items,
        .footer {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header td {
            vertical-align: top;
            padding: 0;
        }

        .brand {
            width: 54%;
            padding-top: 4px;
        }

        .title-block {
            width: 46%;
            text-align: right;
            padding-top: 2px;
        }

        .brand-inner {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .brand-logo {
            width: 56px;
            padding-right: 8px;
            vertical-align: top;
        }

        .brand-logo img {
            width: 50px;
            height: auto;
            display: block;
            margin-top: 2px;
        }

        .company-name {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #3f3f3f;
            line-height: 1.1;
        }

        .company-address {
            margin-top: 2px;
            white-space: pre-line;
            color: #3f3f3f;
            font-size: 12px;
            line-height: 1.15;
        }

        .title {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.2px;
            line-height: 1;
            color: #35373a;
        }

        .status-text {
            margin-top: 4px;
            font-size: 16px;
            font-weight: 700;
            color: #c97e00;
            text-transform: uppercase;
            line-height: 1.08;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .section-gap {
            height: 8px;
        }

        .bill-meta td {
            vertical-align: top;
            padding: 0;
        }

        .bill-to {
            width: 50%;
            padding-right: 8px;
        }

        .bill-to-label {
            display: inline-block;
            background: #c47d00;
            color: #fff;
            padding: 5px 8px;
            min-width: 140px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
        }

        .bill-to-content {
            margin-top: 7px;
            color: #3f3f3f;
            font-size: 12px;
            line-height: 1.28;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .bill-to-client {
            font-weight: 700;
        }

        .meta {
            width: 50%;
            padding-top: 0;
        }

        .meta-line {
            font-size: 12px;
            color: #3f3f3f;
            line-height: 1.22;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .meta-label {
            font-weight: 700;
        }

        .items {
            margin-top: 8px;
        }

        .items th,
        .items td {
            border: 1px solid #d0d0d0;
            padding: 6px 7px;
            font-size: 11px;
            color: #3f3f3f;
            line-height: 1.2;
        }

        .items th {
            background: #c47d00;
            color: #fff;
            text-transform: uppercase;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0;
            white-space: nowrap;
        }

        .items .description {
            width: 36%;
            text-align: left;
        }

        .items .qty {
            width: 12%;
            text-align: right;
            white-space: nowrap;
        }

        .items .unit {
            width: 26%;
            text-align: right;
            white-space: nowrap;
        }

        .items .amount {
            width: 26%;
            text-align: right;
            white-space: nowrap;
        }

        .total-row td {
            background: #e0e0e0;
            font-weight: 700;
            font-size: 12px;
        }

        .total-label {
            text-align: right;
        }

        .total-value {
            text-align: right;
            white-space: nowrap;
        }

        .footer {
            margin-top: 11px;
        }

        .thank-you {
            font-size: 10px;
            color: #777;
        }

        .terms {
            margin-top: 8px;
            font-size: 10px;
            color: #777;
        }

        .terms .label,
        .wire .label {
            font-weight: 700;
        }

        .wire {
            margin-top: 6px;
            font-size: 10px;
            color: #777;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .muted {
            color: #8a8a8a;
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
        <table class="header">
            <tr>
                <td class="brand">
                    <table class="brand-inner">
                        <tr>
                            <td class="brand-logo">
                                @if ($logoPath)
                                    <img src="{{ $logoPath }}" alt="Ecco logo">
                                @endif
                            </td>
                            <td>
                                <div class="company-name">Ecco Outsourcing Group</div>
                                <div class="company-address">{{ $companyAddress }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="title-block">
                    <div class="title">INVOICE</div>
                    <div class="status-text">{{ $statusText }}</div>
                </td>
            </tr>
        </table>

        <div class="section-gap"></div>

        <table class="bill-meta">
            <tr>
                <td class="bill-to">
                    <div class="bill-to-label">BILL TO:</div>
                    <div class="bill-to-content">
                        @if (filled($billContact))
                            {{ $billContact }}
                            <br>
                        @endif
                        <span class="bill-to-client">{{ $billClient }}</span>
                        @if ($billAddress !== '')
                            <br>
                            {{ $billAddress }}
                        @endif
                    </div>
                </td>
                <td class="meta">
                    <div class="meta-line"><span class="meta-label">Invoice #:</span> {{ $invoice->number ?? '-' }}</div>
                    <div class="meta-line"><span class="meta-label">Invoice Date:</span> {{ $invoiceDate }}</div>
                    <div class="meta-line"><span class="meta-label">File Sent At:</span> {{ $fileSentAt }}</div>
                    <div class="meta-line"><span class="meta-label">Publication:</span> {{ $publication }}</div>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
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
Routing: {{ $bankRouting }} - Account: {{ $bankAccount }} - Wire: {{ $bankWire }}</div>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
