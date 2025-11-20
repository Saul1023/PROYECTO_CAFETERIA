<div class="row">
    {{-- Ventas --}}
    <div class="col-md-3">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Ventas</h6>
                <p>Total: {{ $estadisticas['ventas']['total'] }}</p>
                <p>Ingresos: Bs {{ number_format($estadisticas['ventas']['ingresos'], 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Reservaciones --}}
    <div class="col-md-3">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Reservaciones</h6>
                <p>Total: {{ $estadisticas['reservaciones']['total'] }}</p>
                <p>Confirmadas: {{ $estadisticas['reservaciones']['confirmadas'] }}</p>
            </div>
        </div>
    </div>

    {{-- Productos --}}
    <div class="col-md-3">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Productos</h6>
                <p>Total: {{ $estadisticas['productos']['total'] }}</p>
                <p>Stock Bajo: {{ $estadisticas['productos']['stock_bajo'] }}</p>
            </div>
        </div>
    </div>

    {{-- Usuarios --}}
    <div class="col-md-3">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Usuarios</h6>
                <p>Total: {{ $estadisticas['usuarios']['total'] }}</p>
                <p>Nuevos: {{ $estadisticas['usuarios']['nuevos'] }}</p>
            </div>
        </div>
    </div>
</div>
