<?php
namespace App\Traits;

use Carbon\Carbon;

trait ApiResponser
{

    /**
     * Undocumented function
     *
     * @param [type] $personalAccessToken
     * @param [type] $message
     * @param integer $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function token($personalAccessToken, $message = null, $code = 200)
	{
		$tokenData = [
			'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString()
		];

		return $this->success($tokenData, $message, $code);
	}


    /**
     * Success Response
     *
     * @param [type] $data
     * @param [type] $message
     * @param integer $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, $message = null, $code = 200)
	{
		return response()->json([
			'status' => 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}


    /**
     * Error Response
     *
     * @param [type] $message
     * @param integer $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function error($message = null, $code = 401, $data = null)
	{
		return response()->json([
			'status'=> 'Error',
			'message' => $message,
			'data' => $data
        ], $code);
    }


    public function serverError()
    {
        return $this->error(
            'There was an unprecedented error occured in the server',
            500
        );
    }

}
