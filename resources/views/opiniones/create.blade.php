@extends('layouts.app')

@section('title', 'Opiniones')

@section('content')
<style>
  .container-form-op{
    max-width:640px;margin:30px auto;background:#1e1e1e;color:white;
    padding:24px;border-radius:12px;box-shadow:0 6px 15px rgba(0,0,0,.4);
  }
  .encabezado-op{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;}
  .encabezado-op h3{color:#00bcd4;margin:0;}
  .btn-op{background:#00bcd4;color:#fff;border:none;border-radius:6px;padding:8px 14px;text-decoration:none}
  .btn-op:hover{background:#0097a7}
  .estrellas{text-align:center;font-size:30px;margin:8px 0 12px;cursor:pointer}
  .star-yellow{color:#FFD700}.star-gray{color:gray}
  .campo{margin-bottom:14px}
  .campo textarea{width:100%;border:none;border-radius:8px;padding:10px;background:#2a2a2a;color:#fff}
  .acciones{display:flex;justify-content:center;gap:10px;flex-wrap:wrap}
</style>

<div class="container-form-op">
  <div class="encabezado-op">
    <h3>Califica tu experiencia</h3>

    {{-- Solo admin/empleado ve el botón de listado --}}
    @if(Auth::check() && in_array(Auth::user()->rol->nombre_rol, ['admin','empleado']))
      <a href="{{ route('opiniones.index') }}" class="btn-op">Ver opiniones</a>
    @endif
  </div>

  @if($errors->any())
    <div style="color:#f44336;background:#4a2323;padding:10px;border-radius:6px;margin-bottom:12px;">
      Revisa los campos.
    </div>
  @endif

  <form action="{{ route('opiniones.guardar') }}" method="POST">
    @csrf

    <label>Calificación</label>
    <div id="estrellas" class="estrellas">
      <span class="star" data-value="1">&#9733;</span>
      <span class="star" data-value="2">&#9733;</span>
      <span class="star" data-value="3">&#9733;</span>
      <span class="star" data-value="4">&#9733;</span>
      <span class="star" data-value="5">&#9733;</span>
    </div>
    <input type="hidden" id="calificacion" name="calificacion" value="{{ old('calificacion',0) }}">

    <div class="campo">
      <label for="comentario">Comentario</label>
      <textarea id="comentario" name="comentario" rows="4" placeholder="Escribe tu experiencia..." required>{{ old('comentario') }}</textarea>
    </div>

    {{-- Si enlazas cliente desde reservas, setéalo hidden --}}
    <input type="hidden" name="id_cliente" value="{{ old('id_cliente') }}">

    <div class="acciones">
      <button type="submit" class="btn-op">Enviar opinión</button>
      <a href="{{ url('/dashboard') }}" class="btn-op" style="background:#333;">Cancelar</a>
    </div>
  </form>
</div>

<script>
  const stars=document.querySelectorAll('#estrellas .star');
  const hidden=document.getElementById('calificacion');
  function pintar(n){stars.forEach((s,i)=>s.className=(i<n)?'star star-yellow':'star star-gray');}
  stars.forEach((star,idx)=>{
    star.addEventListener('click',()=>{hidden.value=idx+1;pintar(idx+1);});
    star.addEventListener('mouseover',()=>pintar(idx+1));
    star.addEventListener('mouseout',()=>pintar(parseInt(hidden.value||0)));
  });
  pintar(parseInt(hidden.value||0));
</script>
@endsection
