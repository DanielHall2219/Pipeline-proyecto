<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function reporteInventario()
    {
        // En esta parte se llaman a los productos y se carga la vista del pdf con los datos y baja el archivo // Comentario por Daniel 
       
        $productos = Inventario::where('estado', 'activo')->get();

        $pdf = Pdf::loadView('reportes.inventario_pdf', compact('productos'));

        return $pdf->download('reporte_inventario.pdf');
    }
}
