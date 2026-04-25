<?php

namespace App\Features\TradingServer\Enums;

enum BookTypeEnum: string
{
    /**
     * A-Book (STP): Las operaciones de los clientes se envían al mercado real o a un Liquidity Provider (LP).
     * El broker no asume riesgo de mercado; gana por spread/comisión.
     */
    case A_BOOK = 'a_book';

    /**
     * B-Book (casa): Las operaciones se internalizan.
     * El broker toma el lado contrario de la operación, es decir, asume el riesgo de mercado directamente.
     * Si el cliente pierde, el broker gana.
     */
    case B_BOOK = 'b_book';
}