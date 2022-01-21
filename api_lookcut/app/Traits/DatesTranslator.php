<?php

namespace App\Traits;

use Jenssegers\Date\Date;

trait DatesTranslator
{
    public function getFechaSolicitudAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }

    public function getEnvFechaDesAttribute($date)
    {
        if ($date != null)
            return Date::parse($date, config('app.timezone'))->format('d-m-Y g:i:s A');
    }

    public function getFechaEntregaAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }

    public function getFechaContabilidadAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }

    public function getFechaMinimaEntregaAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }

    public function getFechaMaximaEntregaAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }

    public function getInvFechaIngAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }

    public function getInvFechaAliAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }

    public function getFechaEmpaqueAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }

    public function getFechaVencimientoAttribute($date)
    {
        if ($date != null)
            return Date::parse($date)->setTimezone('America/Bogota')->format('d-m-Y g:i:s A');
    }
}
