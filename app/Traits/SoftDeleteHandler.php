<?php

namespace App\Traits;

trait SoftDeleteHandler {
    public function handleSoftDelete($item, $entityName = 'item') {
        $item->delete();
        $message = auth()->user()->role === 'admin'
            ? ucfirst($entityName) . ' movido(a) para a reciclagem.'
            : 'Pedido de eliminação de ' . $entityName . ' submetido para aprovação.';
        return $message;
    }
}
