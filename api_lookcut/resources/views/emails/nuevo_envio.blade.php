@component('mail::message')
### Referencia de Envio: {{$envio->env_codigo}}
### Cliente: {{ $envio->usu_solicitud->usu_nombre }}
#### Destinatario
- **Nombre**: {{$envio->nombre_dest}}
- **Dirección**: {{$envio->direccion_dest}}
- **Ciudad**: {{$envio->municipio->municipio_departamento}}, {{$envio->municipio->dane}}
- **Teléfono**: {{$envio->telefono}}
- **Fecha De Solicitud**: {{$envio->fecha_solicitud}}
@component('mail::table')
| EAN                                            | CANTIDAD            | ESTADO             |
|:----------------------------------------------:|:-------------------:|:------------------:|
@foreach($agrupados as $items)
@foreach($items as $item)
|{{ $item->first()->referencia->codigo_barras }}|{{ $item->count() }}|{{$item->first()->estado->est_descripcion}}         |
@endforeach
@endforeach
@endcomponent
@endcomponent
