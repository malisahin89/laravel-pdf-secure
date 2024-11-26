@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <hr>

                    <!-- PDF Yükleme Formu -->
                    <form action="{{ route('pdf.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="pdf_file">{{ __('Upload PDF') }}</label>
                            <input type="file" class="form-control" name="pdf_file" id="pdf_file" accept=".pdf" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                    </form>

                    <hr>

                    <h5>{{ __('Uploaded PDFs') }}</h5>
                    <ul class="list-group">
                        @if (isset($pdfs) && $pdfs->isNotEmpty())
                            @foreach ($pdfs as $pdf)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <h3>{{ $pdf->file_name }}</h3>
                                    <div>
                                        <a href="{{ route('pdf.show', $pdf->unikey) }}" class="btn btn-info btn-sm" target="_blank">Görüntüle</a>
                                        <a href="{{ route('pdf.download', $pdf->unikey) }}" class="btn btn-primary btn-sm">İndir</a>

                                        <!-- Silme Formu -->
                                        <form action="{{ route('pdf.delete') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pdf->unikey }}">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('PDF\'yi silmek istediğinize emin misiniz?')">Sil</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <p>{{ __('No PDFs available.') }}</p>
                        @endif
                    </ul>

                    <!-- Başarılı mesaj için alan -->
                    <div id="responseMessage" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
