<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Despacho;
use App\Models\DespachoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DespachoController extends Controller
{
    public function index(Request $request)
    {
        $despachos = Despacho::with(['cliente', 'items'])
            ->when($request->cliente_id, fn ($q, $v) => $q->where('cliente_id', $v))
            ->when($request->estado,     fn ($q, $v) => $q->where('estado', $v))
            ->when($request->fecha_desde, fn ($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($request->fecha_hasta, fn ($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->when($request->search,     fn ($q, $v) => $q->where('numero_factura', 'like', "%$v%"))
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $clientes = Cliente::activos()->orderBy('nombre')->get();

        return view('despachos.index', compact('despachos', 'clientes'));
    }

    public function create()
    {
        $clientes  = Cliente::activos()->orderBy('nombre')->get();
        $productos = Producto::activos()->orderBy('nombre')->get();
        $nextFactura = Despacho::siguienteNumeroFactura();

        return view('despachos.create', compact('clientes', 'productos', 'nextFactura'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_factura'              => 'required|string|max:50|unique:despachos,numero_factura',
            'cliente_id'                  => 'required|exists:clientes,id',
            'fecha'                       => 'required|date',
            'estado'                      => 'required|in:pendiente,aprobado,despachado',
            'observaciones'               => 'nullable|string|max:1000',
            'archivo_adjunto'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'items'                       => 'required|array|min:1',
            'items.*.producto_id'         => 'required|exists:productos,id',
            'items.*.lote'                => 'required|string|max:50',
            'items.*.cantidad'            => 'required|integer|min:1',
            'items.*.fecha_vencimiento'   => 'required|date|after:today',
        ]);

        DB::transaction(function () use ($request) {
            $archivoPath = null;
            if ($request->hasFile('archivo_adjunto')) {
                $archivoPath = $request->file('archivo_adjunto')
                    ->store('despachos', 'local');
            }

            $despacho = Despacho::create([
                'numero_factura'  => $request->numero_factura,
                'cliente_id'      => $request->cliente_id,
                'fecha'           => $request->fecha,
                'estado'          => $request->estado,
                'observaciones'   => $request->observaciones,
                'archivo_adjunto' => $archivoPath,
                'user_id'         => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $despacho->items()->create([
                    'producto_id'      => $item['producto_id'],
                    'lote'             => $item['lote'],
                    'cantidad'         => $item['cantidad'],
                    'fecha_vencimiento'=> $item['fecha_vencimiento'],
                ]);
            }
        });

        return redirect()->route('despachos.index')
            ->with('success', 'Despacho creado correctamente.');
    }

    public function show(Despacho $despacho)
    {
        $despacho->load(['cliente', 'items.producto', 'user']);
        return view('despachos.show', compact('despacho'));
    }

    public function edit(Despacho $despacho)
    {
        $despacho->load(['items.producto']);
        $clientes  = Cliente::activos()->orderBy('nombre')->get();
        $productos = Producto::activos()->orderBy('nombre')->get();

        return view('despachos.edit', compact('despacho', 'clientes', 'productos'));
    }

    public function update(Request $request, Despacho $despacho)
    {
        $request->validate([
            'numero_factura'              => 'required|string|max:50|unique:despachos,numero_factura,' . $despacho->id,
            'cliente_id'                  => 'required|exists:clientes,id',
            'fecha'                       => 'required|date',
            'estado'                      => 'required|in:pendiente,aprobado,despachado',
            'observaciones'               => 'nullable|string|max:1000',
            'archivo_adjunto'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'items'                       => 'required|array|min:1',
            'items.*.producto_id'         => 'required|exists:productos,id',
            'items.*.lote'                => 'required|string|max:50',
            'items.*.cantidad'            => 'required|integer|min:1',
            'items.*.fecha_vencimiento'   => 'required|date',
        ]);

        DB::transaction(function () use ($request, $despacho) {
            $archivoPath = $despacho->archivo_adjunto;

            if ($request->hasFile('archivo_adjunto')) {
                if ($archivoPath) {
                    Storage::disk('local')->delete($archivoPath);
                }
                $archivoPath = $request->file('archivo_adjunto')
                    ->store('despachos', 'local');
            }

            $despacho->update([
                'numero_factura'  => $request->numero_factura,
                'cliente_id'      => $request->cliente_id,
                'fecha'           => $request->fecha,
                'estado'          => $request->estado,
                'observaciones'   => $request->observaciones,
                'archivo_adjunto' => $archivoPath,
            ]);

            $despacho->items()->delete();

            foreach ($request->items as $item) {
                $despacho->items()->create([
                    'producto_id'       => $item['producto_id'],
                    'lote'              => $item['lote'],
                    'cantidad'          => $item['cantidad'],
                    'fecha_vencimiento' => $item['fecha_vencimiento'],
                ]);
            }
        });

        return redirect()->route('despachos.show', $despacho)
            ->with('success', 'Despacho actualizado correctamente.');
    }

    public function destroy(Despacho $despacho)
    {
        if ($despacho->archivo_adjunto) {
            Storage::disk('local')->delete($despacho->archivo_adjunto);
        }
        $despacho->delete();

        return redirect()->route('despachos.index')
            ->with('success', 'Despacho eliminado.');
    }

    public function descargarAdjunto(Despacho $despacho)
    {
        abort_unless($despacho->archivo_adjunto && Storage::disk('local')->exists($despacho->archivo_adjunto), 404);
        return Storage::disk('local')->download($despacho->archivo_adjunto);
    }

    public function exportar(Request $request)
    {
        $despachos = Despacho::with(['cliente', 'items.producto'])
            ->when($request->cliente_id,  fn ($q, $v) => $q->where('cliente_id', $v))
            ->when($request->estado,      fn ($q, $v) => $q->where('estado', $v))
            ->when($request->fecha_desde, fn ($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($request->fecha_hasta, fn ($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->orderBy('fecha')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Libro Psicotrópicos');

        // Título
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'LIBRO DE CONTROL DE PSICOTRÓPICOS — ' . config('app.name'));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Cabeceras
        $headers = [
            'FECHA',
            'LOTE',
            'PRINCIPIO ACTIVO',
            'MARCA',
            'MG',
            'COMP.',
            'DESCRIPCIÓN',
            'Columna2',
            'Columna1',
            'Columna12',
            'SALDO ANTERIOR',
            'ENTRADA',
            'SALIDA',
            'SALDO',
        ];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}2", $h);
        }
        $sheet->getStyle('A2:N2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6366F1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);

        $row = 3;
        $saldo = 0;
        $sicmPropio = 'SICM 17774';
        foreach ($despachos as $d) {
            foreach ($d->items as $item) {
                $saldoAnterior = (int) $item->cantidad;
                $entrada = 0;
                $salida = 0;
                $saldo = $saldo + $saldoAnterior;

                $registroSanitario = $item->producto->registro_sanitario;
                $descripcion = 'FACTURA ' . $d->numero_factura;
                if (!empty($registroSanitario)) {
                    $descripcion .= '; ' . $registroSanitario;
                }

                $sheet->setCellValue("A{$row}", $d->fecha->format('d/m/Y'));
                $sheet->setCellValue("B{$row}", $item->lote);
                $sheet->setCellValue("C{$row}", $item->producto->principio_activo);
                $sheet->setCellValue("D{$row}", $item->producto->nombre);
                $sheet->setCellValue("E{$row}", $item->producto->concentracion);
                $sheet->setCellValue("F{$row}", $item->producto->presentacion);
                $sheet->setCellValue("G{$row}", $descripcion);
                $sheet->setCellValue("H{$row}", $d->cliente->nombre);
                $sheet->setCellValue("I{$row}", $sicmPropio);
                $sheet->setCellValue("J{$row}", $d->cliente->sicm ?? '');
                $sheet->setCellValue("K{$row}", $saldoAnterior);
                $sheet->setCellValue("L{$row}", $entrada);
                $sheet->setCellValue("M{$row}", $salida);
                $sheet->setCellValue("N{$row}", $saldo);

                if ($row % 2 === 0) {
                    $sheet->getStyle("A{$row}:N{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
                    ]);
                }
                $row++;
            }
        }

        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'libro_psicotropicos_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportarUno(Despacho $despacho)
    {
        $despacho->load(['cliente', 'items.producto', 'user']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Despacho');

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DESPACHO — ' . $despacho->numero_factura);
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $info = [
            ['Cliente',  $despacho->cliente->nombre],
            ['RIF',      $despacho->cliente->rif],
            ['Fecha',    $despacho->fecha->format('d/m/Y')],
            ['Estado',   ucfirst($despacho->estado)],
            ['Creado por', $despacho->user->name],
        ];
        $r = 2;
        foreach ($info as [$label, $value]) {
            $sheet->setCellValue("A{$r}", $label);
            $sheet->setCellValue("B{$r}", $value);
            $sheet->getStyle("A{$r}")->getFont()->setBold(true);
            $r++;
        }

        $r++;
        $headers = ['Producto', 'Concentración', 'Presentación', 'Lote', 'Cantidad', 'Vencimiento'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}{$r}", $h);
        }
        $sheet->getStyle("A{$r}:F{$r}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6366F1']],
        ]);
        $r++;

        foreach ($despacho->items as $item) {
            $sheet->setCellValue("A{$r}", $item->producto->nombre);
            $sheet->setCellValue("B{$r}", $item->producto->concentracion);
            $sheet->setCellValue("C{$r}", $item->producto->presentacion);
            $sheet->setCellValue("D{$r}", $item->lote);
            $sheet->setCellValue("E{$r}", $item->cantidad);
            $sheet->setCellValue("F{$r}", $item->fecha_vencimiento->format('d/m/Y'));
            $r++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'despacho_' . $despacho->numero_factura . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
