@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>Подключение двухфакторной авторизации </h2>
    </div>

<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                

                <div class="panel-body">
                    <center>
                    Откройте мобильное приложение 2FA и сканируйте следующий QR-код::
                    <br />
                    <img alt="Image of QR barcode" src="{{ $imageDataUri }}" />

                    <br />
                    Если ваше мобильное приложение 2FA не поддерживает QR-коды, введите следующий номер:<br /> <code>{{ $secret }}</code>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection