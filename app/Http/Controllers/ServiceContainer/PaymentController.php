<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers\ServiceContainer;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Log as FacadesLog;

class PaymentController extends Controller
{
    protected $paymentGateway;
    
    // Dependency injection through constructor
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }
    
    // Using the default gateway
    public function pay(Request $request)
    {
        $success = $this->paymentGateway->charge($request->amount);
        
        return response()->json(['success' => $success]);
    }
    
    // Using specific gateway (alternative approach)
    public function payWithGateway(Request $request)
    {
        $gateway = "paypal"; // or "stripe", etc.
        $paymentGateway = app('payment.' . $gateway);
        $success = $paymentGateway->charge(345.5);
        Log::info("Payment made with {$gateway} gateway: " . ($success ? 'Success' : 'Failed'));
        return response()->json(['success' => $success]);
    }
}