<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class Handler extends ExceptionHandler
{

    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
    \Illuminate\Auth\AuthenticationException::class,
    \Illuminate\Auth\Access\AuthorizationException::class,
    \Symfony\Component\HttpKernel\Exception\HttpException::class,
    \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
     {
         $this->renderable(function (ValidationException $e, $request) {
             return $this->errorResponse($e->validator->errors()->getMessages(), 422);
         });

         $this->renderable(function (ModelNotFoundException $e, $request) {
             $modelName = strtolower(class_basename($e->getModel()));
             return $this->errorResponse('Does not exists any {$modelName} with the specified identificator', 404);
         });

         $this->renderable(function (AuthenticationException  $e, $request) {
             return $this->errorResponse('Unaunthenticated... Please Login First', 401);
         });

         $this->renderable(function (AuthorizationException  $e, $request) {
             return $this->errorResponse($e->getMessage(), 403);
         });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return $this->errorResponse('The specified ID/URL cannot be found', 404);
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return $this->errorResponse('The specified method for the request is invalid', 405);
        });
        $this->renderable(function (HttpException $e, $request) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        });

        $this->renderable(function (QueryException $e, $request) {
           $errorCode = $e->errorInfo[1];
           if ($errorCode == 1451) {
               return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource', 409);
           }
        });

        // $this->renderable(function (RequestException $e, $request ) {
        //     return $this->errorResponse($e->getMessage(), 500 );
        // });
        $this->renderable(function (RouteNotFoundException $e, $request ) {
            return $this->errorResponse($e->getMessage(), 500 );
        });




    }



}
