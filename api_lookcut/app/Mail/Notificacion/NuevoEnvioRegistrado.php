<?php

namespace App\Mail\Notificacion;

use App\Models\LogisticaBinoc\Envio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevoEnvioRegistrado extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $envio;
    public $agrupados;

    /**
     * Create a new message instance.
     *
     * @param Envio $envio
     */
    public function __construct(Envio $envio)
    {
        $this->envio = $envio;
        $this->agrupados = $envio->inventario->groupBy(['est_codigo', 'ref_codigo']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.nuevo_envio')
            ->subject("Se registro un nuevo envío {$this->envio->env_codigo}.");
    }
}
