<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\QueryException;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

$apicontroller=new ApiController();

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions)use ($apicontroller) {

        //------exeption

//        $exceptions->render(function (QueryException $ex)use ($apicontroller){
//            DB::rollBack();
////            return 'محتوا وجود ندارد';
//            return $apicontroller->errorResponse('محتوای درخواستی وجود ندارد',400);
//        });
//        $exceptions->render(function (ModelNotFoundException $ex)use ($apicontroller){
//            DB::rollBack();
////
//            return $apicontroller->errorResponse('مدل مورد نظر یافت نشد',400);
//        });
//        $exceptions->render(function (MethodNotAllowedException $ex)use ($apicontroller){
//            DB::rollBack();
//            return $apicontroller->errorResponse('درخواست http صحیح نیست');
//        });
        //----handle all exeption for more information
        $exceptions->render(function (Throwable $ex)use ($apicontroller){
DB::rollBack();
            return $apicontroller->errorResponse($ex->getMessage(),500);
        });


    })->create();
