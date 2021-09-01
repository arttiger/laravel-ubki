<?php

namespace Arttiger\Ubki\Traits;

use Arttiger\Ubki\Facades\Ubki;

trait IntegratorUbki
{
    /**
     * Get Report UBKI.
     *
     * @param $params
     * @return mixed
     */
    public function ubki($params = [])
    {
        if (method_exists($this, 'ubkiAttributes')) {
            $this->ubkiAttributes();
        }

        return Ubki::getReport($this->getAttributes(), $params);
    }

    /**
     * Send Report to UBKI.
     *
     * @param $params
     * @return mixed
     */
    public function ubki_upload($params = [])
    {
        if (method_exists($this, 'ubkiAttributes')) {
            $this->ubkiAttributes($params);
        }

        return Ubki::sendReport($this->getAttributes(), $params);
    }

    /**
     * Get size request to UBKI.
     *
     * @param $params
     * @return mixed
     */
    public function ubki_size_request($params = [])
    {
        if (method_exists($this, 'ubkiAttributes')) {
            $this->ubkiAttributes($params);
        }

        return Ubki::getSizeRequest($this->getAttributes(), $params);
    }
}
