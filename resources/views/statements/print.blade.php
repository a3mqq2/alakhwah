<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>@yield('title') شركة الإخوة للتقسيط </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link href="{{ asset('/assets/css/style-a4.css') }}" rel="stylesheet" type="text/css">
    @php $landscape = 1; @endphp
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <style>
        @page { size: "{{ isset($paper_size) ? $paper_size : 'A4' }}" }
        body { font-family: 'Cairo', sans-serif; text-align: center !important; font-size: 10px !important; }
        * { font-size: 10px !important; }

        /* Darkened colors for header cells */
        .bg-danger-dark { background-color: #b02a37 !important; color: white; }
        .bg-success-dark { background-color: #218838 !important; color: white; }
        .bg-warning-dark { background-color: #856404 !important; color: white; }
        .bg-dark-header { background-color: #343a40 !important; color: white; }
    </style>
    @if(isset($landscape))
    <style>
        @media print { @page { size: landscape } }
    </style>
    @endif
</head>

@php $pages = $unpaidPayments->chunk(16); @endphp

@for ($i = 0; $i < count($pages); $i++)
<body class="{{ isset($paper_size) ? $paper_size : 'A4' }} @if(isset($landscape)) landscape @endif">
    @if(!isset($blank))
    <section class="sheet padding-10mm" dir="rtl" id="invoice">
        @if ($i == 0)
        <div class="text-center">
            <img src="{{ asset('/logos/logo-dark-vertical.png') }}" width="200" />
        </div>
        <hr>
        <div class="row">
            <h4 class="text-center font-weight-bold"><strong>كشف الآقساط الغير مدفوعة من شهر {{ request('from_month') }} الى شهر {{ request('to_month') }}</strong></h4>
        </div>
        @endif
        
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center !important;" class="bg-dark-header">#</th>
                    <th style="text-align: center !important;" class="bg-dark-header">رقم العقد</th>
                    <th style="text-align: center !important;" class="bg-dark-header">الزبون</th>
                    <th style="text-align: center !important;" class="bg-dark-header">رقم الحساب المصرفي</th>
                    <th style="text-align: center !important;" class="bg-dark-header">وصف العقد</th>
                    <th style="text-align: center !important;" class="bg-dark-header">شهر الإستحقاق</th>
                    <th style="text-align: center !important;" class="bg-danger-dark">القيمة المستحقة</th>
                    <th style="text-align: center !important;" class="bg-success-dark">القيمة المدفوعة</th>
                    <th style="text-align: center !important;" class="bg-warning-dark">المتبقي</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages[$i] as $index => $contract)
                <tr>
                    <td style="text-align: center !important;">{{ $index + 1 }}</td>
                    <td style="text-align: center !important;">{{ $contract['id'] ?? 'N/A' }}</td>
                    <td style="text-align: center !important;">{{ $contract['customer'] ?? 'N/A' }}</td>
                    <td style="text-align: center !important;">{{ $contract['bank_number'] ?? 'N/A' }}</td>
                    <td style="text-align: center !important;">{{ $contract['contract_description'] ?? 'N/A' }}</td>
                    <td style="text-align: center !important;">{{ $contract['due_month'] ?? 'N/A' }}</td>
                    <td style="text-align: center !important;background:#f5e4e6">{{ $contract['monthly_deduction'] ?? 0 }}</td>
                    <td style="text-align: center !important;background:#e0fae5">{{ $contract['paid'] ?? 0 }}</td>
                    <td style="text-align: center !important;background:#fff2cd">{{ $contract['due'] ?? 0 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">لم يتم العثور على اي سجلات</td>
                </tr>
                @endforelse

                <!-- Display totals on the last page only -->
                @if ($i == count($pages) - 1)
                <tr>
                    <th colspan="6" class="text-center" style="background: #e6e6e6 !important">الإجماليات</th>
                    <th class="text-center bg-danger-dark">{{ $totalDue }}</th>
                    <th class="text-center bg-success-dark">{{ $totalPaid }}</th>
                    <th class="text-center bg-warning-dark">{{ $totalRemaining }}</th>
                </tr>
                @endif
            </tbody>
        </table>
    </section>
    @else
    @yield("content")
    @endif
</body>
@endfor

@if(request('download'))
<script>
    const invoice = this.document.getElementById("invoice");

    var opt = {
        margin: 0,
        pagesplit: true,
        filename: 'file.pdf',
        image: { type: 'svg', quality: 1 },
        html2canvas: { scale: 1 },
        jsPDF: { unit: 'cm', format: 'A4', orientation: 'portrait' }
    };
    html2pdf().from(invoice).set(opt).then(function(pdf) {
        setTimeout(() => { location.href = '/'; }, 1000);
    }).save();
</script>
@else
<script>
    setTimeout(() => { window.print(); }, 400);
</script>
@endif

</html>
