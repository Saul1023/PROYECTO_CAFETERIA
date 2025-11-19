@extends('layouts.cliente')

@section('title', 'Mis Reservaciones')

@php
$content = '
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>
                        Mis Reservaciones
                    </h4>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                        <h4 class="mt-3 text-muted">No tienes reservaciones</h4>
                        <p class="text-muted">Haz tu primera reservación</p>
                        <a href="'.route('cliente.reservar').'" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Nueva Reservación
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
@endphp