<?php namespace App\Http\Controllers;

use Strava\API\OAuth;
use Strava\API\Exception as StravaException;
use Illuminate\Http\Request;

class WelcomeController extends Controller {

	public function __construct()
	{
		$this->middleware('guest');
	}

	public function index(Request $request)
	{
		try {
		    $options = array(
		        'clientId'     => config('api.strava.client_id'),
		        'clientSecret' => config('api.strava.client_secret'),
		        'redirectUri'  => $request->url()
		    );
		    $oauth = new OAuth($options);

		    if (! $request->input('code')) {
		        return view('auth/connect', [
		        	'connectionUrl' => $oauth->getAuthorizationUrl()
		        ]);
		    } else {
		        $token = $oauth->getAccessToken('authorization_code', array(
		            'code' => $_GET['code']
		        ));
		        return view('auth/success', [
		        	'token' => $token
		        ]);
		    }
		} catch(StravaException $e) {
		    abort(500, $e->getMessage());
		}
	}

}
