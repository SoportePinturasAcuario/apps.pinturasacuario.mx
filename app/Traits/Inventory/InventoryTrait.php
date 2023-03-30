<?php

namespace Apps\Traits\Inventory;

// Models
use Apps\Models\Inventory\Article;

trait InventoryTrait {

    public function structureGenerate($inventory) {

        $articles = array_reduce(Article::all()->toArray(), function($acc, $el) {
            $acc[$el['code']] = $el;
            return $acc;
        }, []);

        $amounts = $inventory->captures->reduce(function($acc, $el) use ($articles) {
            if (isset($acc[$el->code])) {
                $acc[$el->code]['amount'] = $acc[$el->code]['amount'] + $el->amount;
            } else {
                $acc[$el->code] = [
                    'code' => $el->code,
                    'amount' => $el->amount,
                    'idnetsuite' => isset($articles[$el->code]) ? $articles[$el->code]['idnetsuite'] : null,
                ];
            }

            return $acc;
        }, []);

        return array_map(function($el){
            return [
                'ID externo' => null,
                'id_interno' => $el['idnetsuite'],
                'NOMBRE NO MAPEAR' => $el['code'],
                'FECHA' => '2021-09-15',
                'PERIODO CONTABLE' => 'sep-21',
                'UBICACION DE AJUSTE' => 'CEDIS PT',
                'UBICACION' => 'CEDIS PT',
                'CUENTA DE AJUSTE' => null,
                'CANTIDAD NUEVA' => $el['amount'],
                'CANTIDAD DE LOTE' => $el['amount'],
                'DEPOSITO' => 'PISO',
                'LOTE' => '15092021',
                'DEPARTAMENTO GENERAL' => 'CEDIS',
                'DEPARTAMENTO LINEA' => 'CEDIS',
                'SUBSIDIARIA' => 'INDUSTRIAL TECNICA DE PINTURAS',
                'ESTADO' => 'GOOD',
            ];
        }, $amounts);
    }
}