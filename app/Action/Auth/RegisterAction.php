<?php

namespace App\Action\Auth;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RegisterAction{
  public function execute(Collection $collection){
    try{
      DB::beginTransaction();
      $user = User::create($collection->all());
      DB::commit();
      return ['status' => true,'data'=>$user];
    }catch(\Exception $e){
      info($e);
      DB::rollBack();
      return ['status' => false,'error'=>$e->getMessage()];
    }
  }
}