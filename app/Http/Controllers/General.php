<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\vote;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\subscriber;
use App\apexCom;
use App\apexBlock;
use App\User;
use App\Http\Controllers\Account;

class General extends Controller
{

    /**
     * search
     * Returns a list of lists of ApexComs, posts and profiles that matches the given query.
     * Success Cases :
     * 1) Return the result successfully.
     * failure Cases:
     * 1) No matches found.
     *
     * @bodyParam query string required The query to be searched for.
     */

    public function search()
    {
        return;
    }




    /**
     * sortPostsBy
     * Returns a list of posts in a given ApexComm sorted either by the votes or by the date.
     * Success Cases :
     * 1) Return the result successfully.
     * failure Cases:
     * 1) ApexCom fullname (ID) is not found.
     * 2) The given parameter is out of the specified values, in this case it uses the default values.
     *
     * @bodyParam apexComID string required The ID of the ApexComm that contains the posts.
     * @bodyParam sortingParam string The sorting parameter, takes a value of ['votes', 'date'], Default is 'date'.
     */

    public function sortPostsBy(Request $request)
    {
        $apexCom = apexCom::findOrFail($request->apexComID);
        
        $sortingParam = $request->input('sortingParam', 'date');
        $sortingParam = ($sortingParam === 'date' || $sortingParam === 'votes') ? $sortingParam : 'date';

        if ($sortingParam === 'date') {

            return $apexCom->posts()->orderBy('posted_at', 'desc');

        } elseif ($sortingParam === 'votes') {

            return DB::raw(
                'SELECT * FROM posts JOIN 
                ( (SELECT SUM(dir) AS votes, postID from votes GROUP BY postID) AS votesTable )
                on id = postID ORDER BY votes DESC'
            );

        }
    }


    /**
     * apexNames
     * Returns a list of the names of the existing ApexComms.
     * Success Cases :
     * 1) Return the result successfully.
     * failure Cases:
     * 1) Return empty list if there are no existing ApexComms.
     */

    public function apexNames()
    {
        return;
    }
  

    /**
     * getSubscribers
     * Returns a list of the users subscribed to a certain ApexComm.
     * Success Cases :
     * 1) Return the result successfully.
     * failure Cases:
     * 1) Return empty list if there are no subscribers.
     * 2) ApexComm Fullname (ID) is not found.
     *
     * @bodyParam ApexCommID string required The ID of the ApexComm that contains the subscribers.
     * @bodyParam _token string required Verifying user ID.
     */

    public function getSubscribers(Request $request)
    {
        $account = new Account();

        // getting the user_id related to the token in the request and validate.
        $user_id = $account->me($request);

        if (!$user_id) {
            return response()->json(['error' => 'invalid user'], 404);
        }

        $apex_id = $request['ApexCommID'];

        // return an error message if the id (fullname) of the apexcom was not found.
        if(!$apex_id){
            return response()->json(['error' => 'ApexComm is not found.'], 404);
        }

        // check if the validated user was blocked from the apexcom.
        $blocked = apexBlock::where([
            ['ApexID', '=',$apex_id],['blockedID', '=',$user_id] ])->count();
            
        // return an error for if the user was blocked from the apexcom.
        if($blocked != 0){
            return response()->json(['error' => 'You are blocked from this Apexcom'], 404);
        }

        // get the subscribers' for the apexcom user IDs.
        $subscribers = subscriber::where('apexID',$apex_id)->get('userID');

        // return the subscribers user IDs.
        return response()->json(compact('subscribers'));
    }
}
