<?php


namespace Hedi\Sentinel\SQL;


use Hedi\Sentinel\Models\UserPositions;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Sentinel;

class QueryWrapper
{
    public static function select( string $query )
    {

        if( !Sentinel::check() ) return null;

        $wrapper_query = "select * from ( ".$query." ) as q";
        $wrapper_where = "";

        $user = Sentinel::getUser();
        $positions = $user->positions;

        foreach ( $positions as $position)
        {
            $model = $position->mapped_model;
            if( $model != null )
            {
                $e = new $model;
                $pk = $e->getKeyName();

                $user_position = UserPositions::where('position_id', '=', $position->id)->where('user_id','=',$user->id)->first();

                if( $user_position != null){

                    $model_list = $user_position->getAttribute('mapped_model_list');

                    foreach ( json_decode( $model_list) as $item){
                        echo $item;
                        if( $item == null or $item == "N/A")
                        {
                            $wrapper_where = $wrapper_where == "" ? " where  (isnull(q.".$pk.") or q.".$pk." = 'N/A' ) " : $wrapper_where ." or (isnull(q.".$pk.")  or q.".$pk." = 'N/A' ) ";
                        }
                        else
                            $wrapper_where = $wrapper_where == "" ? " where  ( q.".$pk." = $item )" : $wrapper_where ." or ( q.".$pk." = $item ) ";
                    }
//                dd(json_decode($model_list));
//                $items = implode(",", json_decode($model_list));
//
//                $wrapper_where = $wrapper_where == "" ? " where  q.".$pk." in ($items)" : $wrapper_where ." or q.".$pk." in ($items)";
                    Log::info($wrapper_query);
                }


            }
        }
        $wrapper_query .= " " . $wrapper_where;
        return  $wrapper_query;
    }
}
