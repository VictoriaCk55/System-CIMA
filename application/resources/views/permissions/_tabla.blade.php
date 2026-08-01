<div class="row mb-3">
    <div class="col-md-5 col-lg-4 col-xl-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="buscarPermiso" class="form-control" placeholder="Buscar permiso..."
                   value="{{ request('search') }}" autocomplete="off">
        </div>
    </div>
</div>

@if($permissions->count() > 0)
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Guard</th>
                <th>Roles asignados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $perm)
            <tr>
                <td>{{ $perm->id }}</td>
                <td><code>{{ $perm->name }}</code></td>
                <td><code>{{ $perm->guard_name }}</code></td>
                <td>
                    @foreach($perm->roles as $role)
                        <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                    @endforeach
                    @if($perm->roles->count() === 0)
                        <span class="text-muted">Sin asignar</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('permissions.edit', $perm) }}" class="btn btn-sm btn-warning" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('permissions.destroy', $perm) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Eliminar el permiso &quot;{{ $perm->name }}&quot;?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $permissions->links() }}
@elseif(request()->filled('search'))
<div class="alert alert-warning text-center mb-0">
    <i class="fas fa-search me-2"></i> No se encontraron permisos.
</div>
@else
<div class="alert alert-info text-center mb-0">
    <i class="fas fa-info-circle me-2"></i> No hay permisos registrados.
</div>
@endif
