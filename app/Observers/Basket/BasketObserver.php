<?php

namespace App\Observers\Basket;

use App\Models\Basket;

class BasketObserver
{
    /**
     * Handle the Basket "created" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function created(Basket $basket)
    {
        //
    }

    /**
     * Handle the Basket "updated" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function updated(Basket $basket)
    {

        //$basket->quantity+=$basket->getOriginal('quantity');
    }

    /**
     * Handle the Basket "deleted" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function deleted(Basket $basket)
    {
        //
    }

    /**
     * Handle the Basket "restored" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function restored(Basket $basket)
    {
        //
    }

    /**
     * Handle the Basket "force deleted" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function forceDeleted(Basket $basket)
    {
        //
    }
}
