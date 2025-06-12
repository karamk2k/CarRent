<?php 

namespace App\Traits;

trait ApiResponse
{
    protected function apiResponse( bool $success=true, string $message='success', $data=[],int $code=200){
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ],$code);
    }
}