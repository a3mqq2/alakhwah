<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.cdnfonts.com/css/dejavu-sans" rel="stylesheet">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 70px;
        }
        .header h2 {
            margin: 10px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px 12px;
            font-size: 12px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .table tr.not-paid {
            background-color: #f8d7da; /* اللون الأحمر */
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 12px;
        }
        .page-number:before {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logos/logo-dark-vertical.png') }}" alt="شعار الشركة">
        <h2>كشف أقساط شهر {{ $month }} مصرف {{ $bankName }}</h2>
        <p>تاريخ: {{ $today }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>رقم العقد</th>
                <th>الوصف</th>
                <th>الزبون</th>
                <th>رقم الهاتف</th>
                <th>رقم حساب المصرف</th>
                <th>شهر بدء الاستقطاع</th>
                <th>شهر الانتهاء</th>
                <th>الاقساط الكلية</th>
                <th>قيمه الاستقطاع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $contract)
                <tr class="{{ $contract->is_payment ? '' : 'not-paid' }}">
                    <td>{{ $contract->id }}</td>
                    <td>{{ $contract->description }}</td>
                    <td>{{ $contract->customer->name }}</td>
                    <td>{{ $contract->customer->phone }}</td>
                    <td>{{ $contract->customer->bank_number }}</td>
                    <td>{{ $contract->start_month }}</td>
                    <td>{{ $contract->end_month }}</td>
                    <td>{{ $contract->installments }} د.ل</td>
                    <td>{{ $contract->monthly_deduction }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        صفحة <span class="page-number"></span>
    </div>
</body>
</html>
