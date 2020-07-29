<?php

namespace Legislateiro\Repositories;

use Illuminate\Support\Facades\Schema;
use Legislateiro\Models\Contrato;

class ContratoRepository
{
    public function __construct(Contrato $model)
    {
        $this->model = $model;
    }

    /**
     * Returns all Contratos.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->all();
        // return $this->model->orderBy('created_at', 'desc')->all();
    }

    /**
     * Returns all paginated Contratos.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated()
    {
        if (isset(request()->dir) && isset(request()->field)) {
            $model = $this->model->orderBy(request()->field, request()->dir);
        } else {
            $model = $this->model->orderBy('created_at', 'desc');
        }

        return $model->paginate(\Illuminate\Support\Facades\Config::get('cms.pagination', 25));
    }

    /**
     * Searches the orders.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function search($payload, $count)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing('orders');
        $query->where('id', '>', 0);
        $query->where('id', 'LIKE', '%'.$payload.'%');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$payload.'%');
        }

        return [$query, $payload, $query->paginate($count)->render()];
    }

    /**
     * Stores Contratos into database.
     *
     * @param array $payload
     *
     * @return Contratos
     */
    public function store($payload)
    {
        return $this->model->create($payload);
    }

    /**
     * Find Contratos by given id.
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Contratos
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find Contratos by given id.
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Contratos
     */
    public function getByCustomer($id)
    {
        return $this->model->where('user_id', '=', $id);
    }

    /**
     * Find Contratos by given id.
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Contratos
     */
    public function getByCustomerAndId($customer, $id)
    {
        return $this->model->where('user_id', $customer)->where('id', $id)->first();
    }

    /**
     * Find Contratos by given id.
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Contratos
     */
    public function getByCustomerAndUuid($customer, $id)
    {
        return $this->model->where('user_id', $customer)->where('uuid', $id)->first();
    }

    /**
     * Updates Contratos into database.
     *
     * @param Contrato $order
     * @param array  $payload
     *
     * @return Contratos
     */
    public function update($order, $payload)
    {
        if (isset($payload['is_shipped'])) {
            $payload['is_shipped'] = true;
        } else {
            $payload['is_shipped'] = false;
        }

        return $order->update($payload);
    }
}
