<?php

namespace Apps\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class CapturistTeam extends Model
{
    protected $connection = 'inventory';

    protected $table = 'capturist_team';

    protected $fillable = ['id', 'capturist_id', 'team_id'];
}
