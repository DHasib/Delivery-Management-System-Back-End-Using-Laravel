<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{

    // If Requested response return success then get the responce with data and return them into a private function
    private function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status'=> 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

        // If Requested response return error then get the responce with error message and return them into a protected function
        protected function errorResponse($message = null, $code = 422)
        {
            return response()->json([
                'status'=>'Error!',
                'errors' => $message,
                'data' => null
            ], $code);
        }

        // If Requested response for the collection of data then get the responce and return them into a protected function
        protected function showData($data, $message = null, $code = 200)
        {
              return $this->successResponse($data,  $message, $code);
        }


         // If Requested response for the spacific data then get the responce and return them into a protected function
        protected function showMessage($message = null, $status = null, $code = 200)
        {
            return response()->json([
                'status'=> $status,
                'message' => $message,
            ], $code);
        }


}
