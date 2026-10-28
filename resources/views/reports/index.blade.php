@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-sliders2-vertical me-2"></i>Generador Universal de Reportes PDF
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('reports.generate.pdf') }}" method="POST" target="_blank">
                            @csrf

                            <!-- 1. Módulo -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">1. Selecciona el Módulo:</label>
                                    <select name="module" id="moduleSelect" class="form-select" onchange="toggleColumns()">
                                        <option value="units" selected>Unidades / Departamentos</option>
                                        <option value="residents">Residentes</option>
                                        <option value="condominiums">Comunidades</option>
                                    </select>
                                </div>

                                <!-- Filtro Comunidad -->
                                <div class="col-md-6" id="condoFilterGroup">
                                    <label class="form-label fw-bold">Filtro por Comunidad:</label>
                                    <select name="condominium_id" class="form-select">
                                        <option value="">-- Todas las comunidades --</option>
                                        @foreach ($condominiums as $condo)
                                            <option value="{{ $condo->id }}">{{ $condo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- 2. Columnas dinámicas -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0">2. Marca los campos a incluir en el
                                        reporte:</label>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 me-3"
                                            onclick="checkAll(true)">Marcar todos</button>
                                        <button type="button"
                                            class="btn btn-sm btn-link text-decoration-none p-0 text-secondary"
                                            onclick="checkAll(false)">Desmarcar</button>
                                    </div>
                                </div>

                                <!-- Campos Unidades -->
                                <div id="cols-units" class="column-group">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[condominium]" value="Comunidad" id="u_condo" checked>
                                                <label class="form-check-label" for="u_condo">Comunidad</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[number]" value="N° Unidad" id="u_number" checked>
                                                <label class="form-check-label" for="u_number">Número de Unidad</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[tower]" value="Torre" id="u_tower" checked>
                                                <label class="form-check-label" for="u_tower">Torre / Bloque</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[type]" value="Tipo" id="u_type" checked>
                                                <label class="form-check-label" for="u_type">Tipo (Depto, Bodega,
                                                    etc.)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[prorata]" value="Prorrateo (%)" id="u_prorata" checked>
                                                <label class="form-check-label" for="u_prorata">Prorrateo (%)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos Residentes -->
                                <div id="cols-residents" class="column-group d-none">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[name]" value="Nombre" id="r_name" disabled>
                                                <label class="form-check-label" for="r_name">Nombre Completo</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[email]" value="Email" id="r_email" disabled>
                                                <label class="form-check-label" for="r_email">Correo Electrónico</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[phone]" value="Teléfono" id="r_phone" disabled>
                                                <label class="form-check-label" for="r_phone">Teléfono / Celular</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[unit]" value="Unidad Asignada" id="r_unit" disabled>
                                                <label class="form-check-label" for="r_unit">Unidad Asignada</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[condominium]" value="Comunidad" id="r_condo"
                                                    disabled>
                                                <label class="form-check-label" for="r_condo">Comunidad</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos Comunidades -->
                                <div id="cols-condominiums" class="column-group d-none">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[name]" value="Nombre" id="c_name" disabled>
                                                <label class="form-check-label" for="c_name">Nombre Comunidad</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[address]" value="Dirección" id="c_address" disabled>
                                                <label class="form-check-label" for="c_address">Dirección</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input col-checkbox" type="checkbox"
                                                    name="columns[rut]" value="RUT / ID" id="c_rut" disabled>
                                                <label class="form-check-label" for="c_rut">RUT / Identificador</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- 3. Formato -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">3. Orientación del PDF:</label>
                                    <select name="orientation" class="form-select">
                                        <option value="portrait" selected>Vertical (Retrato)</option>
                                        <option value="landscape">Horizontal (Apaisado)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>Generar y Descargar PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleColumns() {
            const module = document.getElementById('moduleSelect').value;
            const condoFilter = document.getElementById('condoFilterGroup');

            // Mostrar / ocultar filtro de comunidad
            condoFilter.style.display = (module === 'condominiums') ? 'none' : 'block';

            // Ocultar y deshabilitar todos los grupos
            document.querySelectorAll('.column-group').forEach(group => {
                group.classList.add('d-none');
                group.querySelectorAll('input').forEach(input => {
                    input.disabled = true;
                });
            });

            // Mostrar y habilitar el grupo seleccionado
            const activeGroup = document.getElementById('cols-' + module);
            if (activeGroup) {
                activeGroup.classList.remove('d-none');
                activeGroup.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                    input.checked = true;
                });
            }
        }

        function checkAll(status) {
            const module = document.getElementById('moduleSelect').value;
            const activeGroup = document.getElementById('cols-' + module);
            if (activeGroup) {
                activeGroup.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = status);
            }
        }
    </script>
@endsection
