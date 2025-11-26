<!-- Sección de Datos Detallados - CORREGIDA -->
<div class="row">
    <div class="col-12">
        <h5 class="text-primary mb-3">Datos Detallados de Ventas</h5>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Método Pago</th>
                        <th class="text-end">Subtotal (Bs.)</th>
                        <th class="text-end">Descuento</th>
                        <th class="text-end">Total (Bs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_iterable($reporteData) && count($reporteData) > 0)
                    @foreach($reporteData as $venta)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $venta->fecha_venta ? \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td>
                            @php
                            $nombreUsuario = 'No asignado';

                            // Opción 1: Si la relación usuario está cargada
                            if (isset($venta->usuario) && is_object($venta->usuario)) {
                            $nombreUsuario = $venta->usuario->nombre_completo ??
                            $venta->usuario->nombre ??
                            $venta->usuario->name ??
                            'Usuario #' . ($venta->usuario->id_usuario ?? $venta->id_usuario ?? 'N/A');
                            }
                            // Opción 2: Si solo tenemos el ID de usuario
                            elseif (isset($venta->id_usuario)) {
                            $nombreUsuario = 'Usuario #' . $venta->id_usuario;
                            }
                            @endphp
                            {{ $nombreUsuario }}
                        </td>
                        <td>
                            <span class="badge bg-success">
                                {{ $venta->metodo_pago ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="text-end">{{ number_format($venta->subtotal ?? 0, 2) }}</td>
                        <td class="text-end">{{ number_format($venta->descuento ?? 0, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($venta->total ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay ventas registradas en el período seleccionado
                        </td>
                    </tr>
                    @endif
                </tbody>
                @if(is_iterable($reporteData) && count($reporteData) > 0)
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">TOTALES:</td>
                        <td class="text-end fw-bold">Bs. {{ number_format($reporteData->sum('subtotal'), 2) }}</td>
                        <td class="text-end fw-bold">Bs. {{ number_format($reporteData->sum('descuento'), 2) }}</td>
                        <td class="text-end fw-bold text-primary">Bs. {{ number_format($reporteData->sum('total'), 2) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
