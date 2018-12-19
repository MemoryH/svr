<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 17:46
 */

namespace App\Models\Mongo;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ChapterModel extends Eloquent
{
    protected $connection = 'mongodb';
    protected $table = 'content';



}