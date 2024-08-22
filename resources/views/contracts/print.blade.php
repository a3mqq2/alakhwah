<!DOCTYPE html>
<html lang="ar" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai&family=Cairo:wght@300&display=swap" rel="stylesheet">
    <link href="{{asset('/assets/css/print_bill.css')}}" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <title> طباعة العقد رقم : #{{$contract->id}} </title>


</head>

<div id="invoice">

    <body class="A4">
        <section class="sheet zima bill">
            <div id="id">#{{$contract->id}}</div>
            <div id="description">{{$contract->description}}</div>
            <div id="installments">{{$contract->installments}}</div>
            <div id="startMonth">{{$contract->start_month}}</div>
            <div id="endMonth">{{date('Y-m', strtotime($contract->end_month))}}</div>
            <div id="monthlyDeduction">{{$contract->monthly_deduction}}</div>
            <div id="bank">{{$contract->bank ? $contract->bank->name : ''}}</div>
            <div id="bank_number">{{$contract->bank ? $contract->bank->number : ''}}</div>
            <div id="customer_name">{{$contract->customer ? $contract->customer->name : ''}}</div>
            <div id="customer_phone">{{$contract->customer ? $contract->customer->phone : ''}}</div>
            <div id="identifier_number">{{$contract->customer ? $contract->customer->identifier_number : ''}}</div>
            <div id="customer_bank_number">{{$contract->customer ? $contract->customer->bank_number : ''}}</div>
        </section>
    </body>


</div>





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.4/jspdf.plugin.autotable.min.js"></script>



@if(request('download'))
<script>
    const invoice = this.document.getElementById("invoice");

    var opt = {
        margin: 0,
        pagesplit: true,
        filename: 'quotation.pdf',
        image: {
            type: 'svg',
            quality: 1
        },
        html2canvas: {
            scale: 1
        },
        jsPDF: {
            unit: 'cm',
            format: 'A4',
            orientation: 'portrait'
        }
    };
    html2pdf().from(invoice).set(opt).then(function(pdf) {
        setTimeout(() => {
            location.href = '/admin/flights/{{$car->flight_id}}';
        }, 1000);
    }).save();
</script>
@else
<script>
    setTimeout(() => {
        window.print()
    }, 400);
</script>
@endif

</html>