        <!-- FIRMAS -->
        <div class="card section-card firmas" style="border: 1px solid #6f42c1; border-radius: 10px; margin-bottom: 1.5rem;">
            <div class="card-header" style="background-color: #6f42c1; color: #fff; font-weight: 600; border-radius: 10px 10px 0 0;">
                <i class="fas fa-signature me-2"></i> Firmas
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Responsable UIA</label>
                        <input type="text" class="form-control @error('responsable_uia') is-invalid @enderror"
                               name="responsable_uia" value="{{ old('responsable_uia', $reporte->responsable_uia ?? '') }}" placeholder="Nombre del responsable">
                        @error('responsable_uia')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cargo</label>
                        <input type="text" class="form-control @error('cargo_responsable') is-invalid @enderror"
                               name="cargo_responsable" value="{{ old('cargo_responsable', $reporte->cargo_responsable ?? '') }}" placeholder="Ej: Técnico en Gestión Ambiental">
                        @error('cargo_responsable')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Directora CIMA</label>
                        <input type="text" class="form-control @error('directora_cima') is-invalid @enderror"
                               name="directora_cima" value="{{ old('directora_cima', $reporte->directora_cima ?? '') }}" placeholder="Nombre de la directora">
                        @error('directora_cima')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cargo</label>
                        <input type="text" class="form-control @error('cargo_directora') is-invalid @enderror"
                               name="cargo_directora" value="{{ old('cargo_directora', $reporte->cargo_directora ?? '') }}" placeholder="Ej: Directora CIMA-UATF">
                        @error('cargo_directora')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTONES -->
        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
            <div>
                <a href="{{ route('reportes.ambiental.index', $proforma) }}" class="btn btn-secondary" style="border-radius: 30px; padding: 10px 25px;">
                    <i class="fas fa-times me-2"></i> Cancelar
                </a>
                @if($reporte && $reporte->exists)
                <button type="button" class="btn btn-warning btn-edit" onclick="toggleEdit(this)" style="border-radius: 30px; padding: 10px 25px; border: none;">
                    <i class="fas fa-edit me-2"></i> Editar
                </button>
                @endif
                @if($reporte && $reporte->exists)
                <a href="{{ route('reportes.ambiental.pdf.' . strtolower($categoria), $reporte) }}" class="btn btn-outline-info ms-2" style="border-radius: 30px; padding: 10px 25px; border-width: 2px;" target="_blank">
                    <i class="fas fa-file-pdf me-2"></i> Ver PDF
                </a>
                @endif
            </div>
            <div class="d-flex gap-2 btn-save-group {{ $reporte && $reporte->exists ? 'd-none' : '' }}">
                <button type="submit" name="accion" value="guardar" class="btn btn-primary"
                        style="border-radius: 30px; padding: 10px 25px; border: none;">
                    <i class="fas fa-save me-2"></i> Guardar
                </button>
            </div>
        </div>
        <script>
            function toggleEdit(btn) {
                var wrapper = document.getElementById('form-wrapper');
                if (wrapper) wrapper.classList.remove('view-mode');
                btn.classList.add('d-none');
                btn.parentElement.parentElement.querySelector('.btn-save-group').classList.remove('d-none');
            }
        </script>
