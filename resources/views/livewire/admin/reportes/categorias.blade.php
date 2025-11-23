<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Productos</th>
            <th>Estado</th>
            <th>Fecha Alta</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporteData as $i => $cat)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>
                <div class="d-flex align-items-center">
                    @if($cat->imagen_url)
                    <img src="{{ $cat->imagen_url_completa }}" alt="{{ $cat->nombre }}" class="rounded me-2" width="40"
                        height="40" style="object-fit: cover;">
                    @else
                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-folder text-muted"></i>
                    </div>
                    @endif
                    <span>{{ $cat->nombre }}</span>
                </div>
            </td>
            <td>
                @if($cat->descripcion)
                <span title="{{ $cat->descripcion }}">
                    {{ Str::limit($cat->descripcion, 50) }}
                </span>
                @else
                <span class="text-muted">Sin descripción</span>
                @endif
            </td>
            <td>
                <span class="badge bg-primary">{{ $cat->productos_count ?? 0 }}</span>
            </td>
            <td>
                <span class="badge bg-{{ $cat->estado ? 'success' : 'danger' }}">
                    {{ $cat->estado ? 'Activa' : 'Inactiva' }}
                </span>
            </td>
            <td>{{ $cat->fecha_creacion ? \Carbon\Carbon::parse($cat->fecha_creacion)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@if(isset($estadisticas) && !empty($estadisticas))
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">📊 Estadísticas de Categorías</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h4 class="text-primary mb-1">{{ $estadisticas['total'] ?? 0 }}</h4>
                            <small class="text-muted">Total Categorías</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h4 class="text-success mb-1">{{ $estadisticas['activas'] ?? 0 }}</h4>
                            <small class="text-muted">Categorías Activas</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h4 class="text-info mb-1">{{ $estadisticas['con_productos'] ?? 0 }}</h4>
                            <small class="text-muted">Con Productos</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h4 class="text-warning mb-1">{{ $estadisticas['sin_productos'] ?? 0 }}</h4>
                            <small class="text-muted">Sin Productos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif