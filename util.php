<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;


$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '172.22.34.116',
    'database'  => '2ch',
    'username'  => '2ch',
    'password'  => 'passwd',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();


class AdminTable extends Model
{
    protected $table = 'admins';
}


class VerifyTable extends Model
{
    protected $table = 'verifys';
}

class BoardTable extends Model
{
    protected $table = 'boards';
}

class ThreadTable extends Model
{
    protected $table = 'threads';
}

class CommentTable extends Model
{
    protected $table = 'comments';
}


function randomHex($length = 32) {
    return bin2hex(random_bytes($length / 2));
}


?>
