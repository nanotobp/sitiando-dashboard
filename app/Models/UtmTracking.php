<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UtmTracking extends Model
{
    use HasUuids;

    protected $table = 'utm_tracking';

    protected $fillable = [
        'click_id',
        'order_id',

        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
    ];

    protected $casts = [
        'utm_source'   => 'string',
        'utm_medium'   => 'string',
        'utm_campaign' => 'string',
        'utm_term'     => 'string',
        'utm_content'  => 'string',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function click()
    {
        return $this->belongsTo(AffiliateClick::class, 'click_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /* ==========================================================
       SCOPES PRO
    ========================================================== */

    public function scopeSource($q, string $source = null)
    {
        return $source ? $q->where('utm_source', $source) : $q;
    }

    public function scopeCampaign($q, string $campaign = null)
    {
        return $campaign ? $q->where('utm_campaign', $campaign) : $q;
    }

    public function scopeMedium($q, string $medium = null)
    {
        return $medium ? $q->where('utm_medium', $medium) : $q;
    }

    public function scopeTerm($q, string $term = null)
    {
        return $term ? $q->where('utm_term', $term) : $q;
    }

    /* ==========================================================
       MÉTRICAS PRO PARA ANALYTICS
    ========================================================== */

    public function trackingSummary()
    {
        return [
            'source'   => $this->utm_source,
            'medium'   => $this->utm_medium,
            'campaign' => $this->utm_campaign,
            'term'     => $this->utm_term,
            'content'  => $this->utm_content,
            'click_id' => $this->click_id,
            'order_id' => $this->order_id,
        ];
    }

    /**
     * Métrica simple utilizada por el dashboard:
     * devuelve una etiqueta con el tracking resumido.
     */
    public function label()
    {
        return sprintf(
            "%s / %s / %s",
            $this->utm_source ?: '-',
            $this->utm_medium ?: '-',
            $this->utm_campaign ?: '-'
        );
    }
}
