<?php

namespace Legislateiro\Services;

use Informate\Models\Refund;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Support\Facades\Config;
use Legislateiro\Repositories\ContratoRepository;

class ContratoService
{
    public function __construct(
        ContratoRepository $contratoRepository
    ) {
        $this->repo = $contratoRepository;
    }

    /**
     * Get all Contratos.
     *
     * @return Collection
     */
    public function all()
    {
        return $this->repo->all();
    }

    /**
     * Get all Contratos.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginated()
    {
        return $this->repo->paginated(\Illuminate\Support\Facades\Config::get('siravel.pagination', 25));
    }

    /**
     * Find the Contrato by ID.
     *
     * @param int $id
     *
     * @return Contratos
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /**
     * Search the orders.
     *
     * @param array $payload
     *
     * @return Collection
     */
    public function search($payload)
    {
        return $this->repo->search($payload, \Illuminate\Support\Facades\Config::get('siravel.pagination', 25));
    }

    /**
     * Create an order.
     *
     * @param array $payload
     *
     * @return Contratos
     */
    public function create($payload)
    {
        $order = $this->repo->store($payload);

        $this->logistics->orderCreated($order);

        return $order;
    }

    /**
     * Update an order.
     *
     * @param int   $id
     * @param array $payload
     *
     * @return Contratos
     */
    public function update($id, $payload)
    {
        $order = $this->find($id);

        if (isset($payload['is_shipped']) && $payload['is_shipped'] !== false) {
            $this->logistics->shipContrato($order);
        }

        return $this->repo->update($order, $payload);
    }

    /**
     * Cancel an order.
     *
     * @param int $id
     *
     * @return Contratos|false
     */
    public function cancel($orderId)
    {
        $order = $this->repo->find($orderId);

        if ($order->status != 'complete') {
            $this->logistics->cancelContrato($order);

            if ($order->hasActiveContratoItems()) {
                $refund = $this->transactions->refund($order->transaction('uuid'), $order->remainingValue());

                if ($refund) {
                    $refundRecord = app(Refund::class)->create(
                        [
                        'transaction_id' => $order->transaction('id'),
                        'provider_id' => $refund->id,
                        'uuid' => Crypto::uuid(),
                        'amount' => $refund->amount,
                        'provider' => 'SierraTecnologia',
                        'charge' => $refund->charge,
                        'currency' => $refund->currency,
                        ]
                    );

                    foreach ($order->items as $item) {
                        $item->update(
                            [
                            'was_refunded' => true,
                            'status' => 'cancelled',
                            'refund_id' => $refundRecord->id,
                            ]
                        );
                    }

                    return $this->update(
                        $order->id, [
                        'status' => 'cancelled',
                        'is_shipped' => false,
                        ]
                    );
                }
            }
        }

        return false;
    }
}
