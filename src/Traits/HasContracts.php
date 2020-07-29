<?php

namespace Legislateiro\Traits;

use Log;


trait HasContacts
{


    /**
     * Contatos
     */
    public function phones()
    {
        return $this->morphToMany('Legislateiro\Models\Digital\Phone', 'phoneable');
    }
    public function emails()
    {
        return $this->morphToMany('Legislateiro\Models\Digital\Email', 'emailable');
    }


    /**
     * Get all of the addresses for the post.
     */
    public function addresses()
    {
        return $this->morphToMany('Locaravel\Models\Address', 'addresseable');
    }

    /**
     * Outros Relatiosn Nad a aver
     *
     * @return void
     */
    public function sitios()
    {
        return $this->morphToMany('Legislateiro\Models\Digital\Sitio', 'sitioable');
    }
}
