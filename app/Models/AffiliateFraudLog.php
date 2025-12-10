<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliateFraudLog extends Model
{
    use HasUuids;

    protected $table = 'affiliate_fraud_logs';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'affiliate_id',
        'click_id',
        'score',
        'reason',
        'ip_address',
        'user_agent',
        'fingerprint',
    ];

    protected $casts = [
        'score'       => 'decimal:2',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',

        // si fingerprint es jsonb, dejalo así:
        // 'fingerprint' => 'array',
    ];

    /* ======================================
       RELACIONES
    ====================================== */

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function click()
    {
        return $this->belongsTo(AffiliateClick::class, 'click_id');
    }

    /* ======================================
       MÉTODOS ÚTILES
    ====================================== */

    /**
     * Permite registrar un evento de fraude fácilmente.
     */
    public static function record(array $data)
    {
        return self::create($data);
    }

    /**
     * Devuelve descripción legible del evento.
     */
    public function summary()
    {
        return sprintf(
            "[%s] Score: %s – %s",
            $this->created_at->format('d/m/Y H:i'),
            $this->score,
            $this->reason
        );
    }
}
