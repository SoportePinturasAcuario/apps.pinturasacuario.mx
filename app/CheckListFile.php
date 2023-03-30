<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class CheckListFile extends Model
{
    protected $connection = 'checklists';
    protected $table = 'archivos';
    protected $fillable = ['checklist_id', 'name', 'gdriveid', 'url'];
}
