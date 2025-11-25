<?php

namespace App\Model;

use Core\Database\DB;

class BaseModel
{
    protected DB $db;
    public function __construct(DB $db)
    {
        $this->db = $db;
    }
}
