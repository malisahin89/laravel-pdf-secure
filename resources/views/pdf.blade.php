@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <hr>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.6/pdfobject.min.js"></script>
                <div id="pdf-viewer" style="height: 500px; border: 1px solid #ccc;"></div>

                <script>
                    // Base64 verisini Data URL formatına çevir
                    var dataURL = "data:application/pdf;base64,{{ $base64PDF }}";

                    // PDF dosyasını göster
                    PDFObject.embed(dataURL, "#pdf-viewer");
                </script>
            </div>
        </div>
    </div>
@endsection
