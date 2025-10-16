@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div style="text-align: center; padding: 50px;">

    <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-top: 40px;">
        <a href="{{ route('reportes.inventario') }}" target="_blank" style="text-decoration: none;">
            <div style="border: 2px solid #00bcd4; padding: 20px 30px; border-radius: 10px; background: #1e1e1e; color: white;">
                <br>Generar reporte de inventario
            </div>
        </a>
    </div>

</div>
@endsection
