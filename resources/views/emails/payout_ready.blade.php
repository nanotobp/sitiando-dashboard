@component('mail::message')

# ¡Hola {{ $affiliate->full_name }}!

Tu liquidación correspondiente al período:

**{{ $payout->period_start }} → {{ $payout->period_end }}**

ya está disponible en tu panel de afiliado.

---

## 🧾 Resumen

- **Total Ventas:** ₲ {{ number_format($payout->total_amount, 0, ',', '.') }}
- **Comisión Total:** ₲ {{ number_format($payout->net_amount, 0, ',', '.') }}
- **Estado:** {{ ucfirst($payout->status) }}
- **Comisiones Incluidas:** {{ count($payout->commission_ids) }}

---

@component('mail::button', ['url' => url('/admin/payouts/'.$payout->id)])
Ver Liquidación
@endcomponent

Si tenés preguntas o necesitás revisar detalles adicionales, estamos para ayudarte.

Gracias por formar parte del ecosistema **Sitiando** 💙

@endcomponent
