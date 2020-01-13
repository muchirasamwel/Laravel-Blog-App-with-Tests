<?php

namespace App;

use App\Scopes\TodaysBlog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;

class Blog extends Model
{
    protected $fillable=[
      'title','content','publisher','user_id'
    ];
    public function user(){
        return $this -> belongsTo(User::class);
    }
    public function scopeofToday($query)
    {
        return $query->where('created_at', '>', Carbon::yesterday()->addHours(24));
    }
    public function scopeofSearch($query,$value)
    {
        return $query->where('title', '=', $value);
    }
    function simple_crypt( $string, $action = 'e' ) {
        // you may change these values to your own
        $secret_key = 'my_simple_secret_key';
        $secret_iv = 'my_simple_secret_iv';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }

        return $output;
    }
    //mutator encryps my content data
    public function setContentAttribute($value)
    {

        return $this->attributes['content']=$this->simple_crypt($value);
    }

    //Accessor decrypts the data before displaying
    public function getContentAttribute($value)
    {
        return $this->attributes['content']= $this->simple_crypt($value,'d');
    }
}
