<?php 
namespace App\Tools;

class Tools {

	public static function ModelPrepare($model,$request,$input=[])
	{
		$new = new $model;
		$primaryKey = $new->getKeyName();
		$table = $new->getTable();
		$fields = $new->getConnection()->getSchemaBuilder()->getColumnListing($table);
		if ($request)
			$input = array_merge($request->all(),$input);

		$id = isset($input[$primaryKey]) ? $input[$primaryKey] : null;
		unset($input[$primaryKey]);
		if ($id)
		{
			$new_q = $model::where($primaryKey,$id);
			$new = $new_q->first();
		}
		else
		{
			$new->setCreatedAt(time());
		}

		foreach ($fields as $k=>$v)
		{
			if (array_key_exists($v,$input))
				$new->$v = $input[$v] === '' ? null : $input[$v];
			if ($v == 'created_by')
				$new->$v = $new->$v ? : \Auth::id();
		}

    	return $new;
	}

	public static function ModelSetOrder($model, $id, $after_id = null) {
		$class = new $model;
		$order = 0;
		if (!$after_id) { //get highest order and increase
			if ($highest = $class->newQuery()->where('Status','<>',2)->orderBy('order','DESC')->first()) {
				$order = $highest->id == $id ? intval($highest->order) : intval($highest->order) + 1;
			}
		}
		else {
			$after_one = $class->newQuery()->where('id','=',$after_id)->first();
			$order = intval($after_one->order);
			$afters = $class->newQuery()->where('Status','<>',2)->where('order',">=",intval($after_one->order))->get();
			foreach ($afters as $item) {
				$item->order++;
				$item->save();
			}
		}
		$obj = $class->newQuery()->where('id','=',$id)->first();
		$obj->order = $order;
		$obj->save();

	}

	public static function GoogleTokenCheck($token) {
		$token_info = null;
		$url = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . urlencode($token);
		$ch = curl_init($url);
	  	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  		$output = curl_exec($ch);
  		$info = curl_getinfo($ch);
  		$http_result = $info['http_code'];
  		curl_close ($ch);
        if ($http_result == 200) {
	        $token_info = json_decode($output,1);
	    }
        return $token_info;
	}
}