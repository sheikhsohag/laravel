<?php
namespace App\Http\Controllers\ServiceContainer;

use App\Http\Controllers\Controller;
use App\Services\LoggerService;

class ExampleController extends Controller
{
    protected $logger;

    public function __construct(LoggerService $logger)
    {
        $this->logger = $logger;
    }

    public function index()
    {
        $this->logger->log('This is a log message from the ExampleController.');
        return response()->json(['message' => 'Logged successfully']);
    }
}