<?php

namespace App\Extensions\Servers\Momentmcreseller;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Helpers\ExtensionHelper;
use App\Models\Extension;
use App\Models\Product;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;
use App\Models\OrderProduct;
use App\Classes\Extensions\Server;
use Illuminate\Support\Facades\Http;

class Momentmcreseller extends Server
{
    
    private $apikey;


    public function __construct(Extension $extension)
    {
        parent::__construct($extension);

        View::addNamespace('Momentmcreseller', base_path('App/Extensions/Servers/Momentmcreseller/views'));

        $this->apikey = ExtensionHelper::getConfig('Momentmcreseller', 'api_key');


    }



    /**
    * Get the extension metadata
    * 
    * @return array
    */
    public function getMetadata()
    {
        return [
            'display_name' => 'Momentmcreseller',
            'version' => '1.0.0',
            'author' => 'Paymenter',
            'website' => 'https://paymenter.org',
        ];
    }
    
    /**
     * Get all the configuration for the extension
     * 
     * @return array
     */
    public function getConfig()
    {
        return [
            
            [
                'name' => 'api_key',
                'type' => 'text',
                'friendlyName' => 'API Key',
                'required' => true
            ]
        ];
    }

    /**
     * Get product config
     * 
     * @param array $options
     * @return array
     */
   public function getProductConfig($options)
    {
        return [
           
            [
                'name' => 'plan',
                'type' => 'dropdown',
                'friendlyName' => 'momentmc plan',
                'required' => true,
                'options' => $this->getNodes()
            ],
            
        ];
    }

private function getNodes()
{
    return [
        ['value' => 1, 'name' => 'Coal'],
        ['value' => 2, 'name' => 'Copper'],
        ['value' => 3, 'name' => 'Iron'],
    ];
}


public function getUserConfig(Product $product)
{
    return [
        [
            'name' => 'ServerType',
            'type' => 'dropdown',
            'friendlyName' => 'ServerType',
            'required' => true,
            'options' => $this->getServerTypes()
        ],
    ];
}

private function getServerTypes()
{
    return [
        ['value' => 1, 'name' => 'Vanilla'],
        ['value' => 2, 'name' => 'Paper'],
        ['value' => 3, 'name' => 'Forge'],
        ['value' => 4, 'name' => 'Bungeecord'],
        ['value' => 5, 'name' => 'Fabric'],

    ];
}


    /**
     * Create a server
     * 
     * @param User $user
     * @param array $params
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @param array $configurableOptions
     * @return bool
     */
 public function createServer($user, $params, $order, $orderProduct, $configurableOptions)
{
    $serverType = $configurableOptions['ServerType'] ?? $params['config']['ServerType'];
    $plan = $configurableOptions['plan'] ?? $params['plan'];

    if (!$plan) {
        ExtensionHelper::error('Momentmcreseller', 'No plan selected.');
        return false;
    }

    // Ensure plan is always in an array format
    $planArray = is_array($plan) ? $plan : [$plan];

    $payload = [
        'product_id' => $planArray, // Plan is now always an array
        'configurable_options' => [
            "0" => [
                "1" => $serverType,
            ],
        ],
    ];

    $apiUrl = 'https://bill.momentmc.com/api/client/v1/invoices/store';
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apikey,
        'Content-Type' => 'application/json',
    ])->post($apiUrl, $payload);

    if ($response->failed()) {
        ExtensionHelper::error('Momentmcreseller', $response->json('message') ?? 'Failed to create invoice.');
        return false;
    }

    $responseData = $response->json();

    if (isset($responseData['data']['invoice']['id'])) {
        $invoiceId = $responseData['data']['invoice']['id'];
        
        ExtensionHelper::setOrderProductConfig('invoice_id', $invoiceId, $orderProduct->id);

        $payApiUrl = "https://bill.momentmc.com/api/client/v1/invoices/{$invoiceId}/pay";
        $payPayload = [
            'payment_method' => 'credits',
        ];
        $payResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apikey,
            'Content-Type' => 'application/json',
        ])->post($payApiUrl, $payPayload);

        if ($payResponse->failed()) {
            ExtensionHelper::error('Momentmcreseller', $payResponse->json('message') ?? 'Failed to pay the invoice.');
            return false;
        }

        return $invoiceId;
    } else {
        ExtensionHelper::error('Momentmcreseller', 'Invoice ID not found in API response.');
        return false;
    }
}




    /**
     * Suspend a server
     * 
     * @param User $user
     * @param array $params
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @param array $configurableOptions
     * @return bool
     */
    public function suspendServer($user, $params, $order, $orderProduct, $configurableOptions)
    {
        return false;
    }

    /**
     * Unsuspend a server
     * 
     * @param User $user
     * @param array $params
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @param array $configurableOptions
     * @return bool
     */
    public function unsuspendServer($user, $params, $order, $orderProduct, $configurableOptions)
    {
        return false;
    }

    /**
     * Terminate a server
     * 
     * @param User $user
     * @param array $params
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @param array $configurableOptions
     * @return bool
     */
  public function terminateServer($user, $params, $order, $orderProduct, $configurableOptions)
{
    // Retrieve the invoice ID from configurable options
    $invoiceId = $params['config']['invoice_id'];

    if (!$invoiceId) {
        ExtensionHelper::error('Momentmcreseller', 'No invoice ID found for the order product.');
        return false;
    }

    $apiUrl = 'https://bill.momentmc.com/api/client/v1/invoices/delete';
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apikey,
        'Content-Type' => 'application/json',
    ])->delete($apiUrl, [
        'invoice_id' => $invoiceId,
    ]);

    if ($response->failed()) {
        ExtensionHelper::error('Momentmcreseller', $response->json('message') ?? 'Failed to terminate server.');
        return false;
    }

    return true;
}

public function getLink($user, $params, $order, $orderProduct): bool|string|null
{
    // Check if the 'invoice_id' exists in the provided parameters
    $invoiceId = $params['config']['invoice_id'] ?? null;

    if (!$invoiceId) {
        ExtensionHelper::error('Momentmcreseller', 'Invoice ID is missing from the product configuration.');
        return null;
    }

    // Construct the desired link with both 'product' and 'invoice_id'
    return route('extensions.momentmcreseller.config', [
        'product' => $orderProduct->id,
        'invoice_id' => $invoiceId,
    ]);
}


public function config($product, $invoiceId, Request $request)
{
    // Fetch the product object
    $orderProduct = OrderProduct::find($product);

   

    if (!$orderProduct) {
        ExtensionHelper::error('Momentmcreseller', 'Invalid product ID provided.');
            return view('Momentmcreseller::Errore.servernotfound');

        return response()->view('Momentmcreseller::error', [
            'message' => 'Invalid product ID.',
        ], 404);
    }

    // Ensure the user has access to this product
    if (!ExtensionHelper::hasAccess($orderProduct, $request->user())) {
        throw new HttpException(403, 'You do not have access to this product.');
    }

    if (!$invoiceId) {
        ExtensionHelper::error('Momentmcreseller', 'Invoice ID is missing from the route.');
        return response()->view('Momentmcreseller::error', [
            'message' => 'Invoice ID is missing from the route.',
        ], 400);
    }

    // API URL and Authorization Header
    $url = 'https://bill.momentmc.com/api/client/v1/ptero/check';

    try {
        // Send a GET request to the API with the invoice ID
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apikey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->get($url, [
            'invoice_id' => $invoiceId,
        ]);

        if ($response->successful()) {
    // Decode the API response
    $data = $response->json();

    // Extract structured data from the response
    $serverState = $data['server_state'] ?? null;
    $status = $data['status'] ?? null;
    $invoiceId = $data['invoice_id'] ?? null;
    $orderId = $data['order_id'] ?? null;
    $orderProductId = $data['order_product_id'] ?? null;
    $productId = $data['product_id'] ?? null;
    $cpuUsage = $data['cpu_usage'] ?? null;
    $ip = $data['ip_alias'] ?? null;
    $port = $data['port'] ?? null;




    // Return structured data to the view
    return view('Momentmcreseller::configure', [
        'serverState' => $serverState,
        'cpuUsage' => $cpuUsage,      
        'status' => $status,
        'invoiceId' => $invoiceId,
        'orderId' => $orderId,
        'orderProductId' => $orderProductId,
        'orderProduct' => $orderProduct,
        'orderProduct' => $product,
        'productId' => $productId,
        'ip' => $ip,
        'port' => $port,
        'title' => 'Product Configuration',
    ]);
} 

        // Handle API Errors
        $responseData = $response->json();
        $errorDetail = $responseData['errors'][0]['detail'] ?? null;
        $statusCode = $response->status();

        if ($errorDetail == 'This server has not yet completed its installation process, please try again later.') {
            return view('Momentmcreseller::Errore.install');
        } elseif ($errorDetail == 'This server is currently suspended and the functionality requested is unavailable.') {
            return view('Momentmcreseller::Errore.suspendu');
        } elseif ($statusCode == 504) {
            return view('Momentmcreseller::Errore.timeout');
        } elseif ($statusCode == 404) {
            return view('Momentmcreseller::Errore.servernotfound');
        }

        // Generic error fallback
        ExtensionHelper::error('Momentmcreseller', 'API response failed. Status: ' . $statusCode . ' Body: ' . $response->body());
        return response()->view('Momentmcreseller::error', [
            'message' => 'Failed to fetch server details from the API.',
        ], 500);

    } catch (Exception $e) {
        ExtensionHelper::error('Momentmcreseller', 'An error occurred: ' . $e->getMessage());
        return response()->view('Momentmcreseller::error', [
            'message' => 'An unexpected error occurred.',
        ], 500);
    }
}


public function console($product, $invoiceId, Request $request)
{
    try {
        // Fetch the product object
        $orderProduct = OrderProduct::find($product);

        if (!$orderProduct) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid product ID.',
            ], 404);
        }

        // Ensure the user has access to this product
        if (!ExtensionHelper::hasAccess($orderProduct, $request->user())) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have access to this product.',
            ], 403);
        }

        if (!$invoiceId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice ID is missing from the request.',
            ], 400);
        }

        // API URL and Authorization Header
        $url = 'https://bill.momentmc.com/api/client/v1/ptero/console';
        $authorization = 'Bearer ' . $this->apikey; // Using class property for API key

        // Send a GET request to the API with the invoice ID
        $response = Http::withHeaders([
            'Authorization' => $authorization,
            'Content-Type' => 'application/json',
        ])->timeout(60)->get($url, [
            'invoice_id' => $invoiceId,
        ]);

        \Log::info('Ptero API Response:', ['status' => $response->status(), 'body' => $response->json()]);

        // Return the full API response
        return response()->json([
            'status' => $response->successful() ? 'success' : 'error',
            'data' => $response->json(),
        ], $response->status());

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
}





public function power(Request $request)
{
    // Extract invoice_id and action from the request
    $invoiceId = $request->input('invoice_id');
    $action = $request->input('action');

    // Validate the power action
    $validActions = ['start', 'stop', 'restart', 'kill'];
    if (!in_array($action, $validActions)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid power action provided. Allowed actions: start, stop, restart, kill.',
        ], 400);
    }

    // Skip database query and directly call the external API
    try {
        $url = 'https://bill.momentmc.com/api/client/v1/ptero/power';
        

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apikey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->post($url, [
            'invoice_id' => $invoiceId,
            'action' => $action,
        ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to execute power action on the server.',
            ], $response->status());
        }
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
}


public function files($product, $invoiceId, Request $request)
{
    // Fetch the product object
    $orderProduct = OrderProduct::find($product);

   

    if (!$orderProduct) {
        ExtensionHelper::error('Momentmcreseller', 'Invalid product ID provided.');
        return response()->view('Momentmcreseller::error', [
            'message' => 'Invalid product ID.',
        ], 404);
    }

    // Ensure the user has access to this product
    if (!ExtensionHelper::hasAccess($orderProduct, $request->user())) {
        throw new HttpException(403, 'You do not have access to this product.');
    }

    if (!$invoiceId) {
        ExtensionHelper::error('Momentmcreseller', 'Invoice ID is missing from the route.');
        return response()->view('Momentmcreseller::error', [
            'message' => 'Invoice ID is missing from the route.',
        ], 400);
    }

   // API URL and Authorization Header
$url = 'https://bill.momentmc.com/api/client/v1/ptero/check';

try {
    // Send a GET request to the API with the invoice ID
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apikey,
        'Content-Type' => 'application/json',
    ])->timeout(60)->get($url, [
        'invoice_id' => $invoiceId,
    ]);

    if ($response->successful()) {
        // Decode the API response
        $data = $response->json();

        // Extract only the status
        $status = $data['status'] ?? null;

        // Return structured data to the view
        return view('Momentmcreseller::files', [
            'status' => $status,
            'title' => 'Product Configuration',
            'orderProduct' => $orderProduct,
            'invoiceId' => $invoiceId,


        ]);
    } else {
        ExtensionHelper::error('Momentmcreseller', 'API response failed. Status: ' . $response->status() . ' Body: ' . $response->body());
        return response()->view('Momentmcreseller::error', [
            'message' => 'Failed to fetch server details from the API.',
        ], 500);
    }
} catch (Exception $e) {
    ExtensionHelper::error('Momentmcreseller', 'An error occurred: ' . $e->getMessage());
    return response()->view('Momentmcreseller::error', [
        'message' => 'An unexpected error occurred.',
    ], 500);
}

}

public function listFiles($product, $invoiceId)
{
    $request = request();

    // Validate product
    $orderProduct = OrderProduct::find($product);
    if (!$orderProduct) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid product ID.',
        ], 404);
    }

    // Validate access
    if (!ExtensionHelper::hasAccess($orderProduct, $request->user())) {
        return response()->json([
            'status' => 'error',
            'message' => 'You do not have access to this product.',
        ], 403);
    }

    // Get path from query and sanitize it
    $filePath = $request->query('path', '/'); // Default to root if not set
    $filePath = preg_replace('/\/+/', '/', $filePath); // Remove duplicate slashes

    // Ensure proper formatting (avoid leading double slashes)
    if ($filePath !== '/' && str_starts_with($filePath, '/')) {
        $filePath = substr($filePath, 1); // Remove leading slash for compatibility
    }

    // API URL (updated to match frontend)
    $url = "https://bill.momentmc.com/api/client/v1/ptero/files?directory=" . urlencode($filePath) . "&invoice_id={$invoiceId}&nocache=" . time();

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apikey,
            'Content-Type'  => 'application/json',
        ])->timeout(60)->get($url);

        // Debugging log
        \Log::info('API Response: ' . $response->body());

        if ($response->successful()) {
            $data = $response->json();
            return response()->json(['status' => 'success', 'data' => $data]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch file list from API.',
            ], 500);
        }
    } catch (Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Unexpected error: ' . $e->getMessage(),
        ], 500);
    }
}

public function downloadFile($product, $invoiceId)
{
    $request = request();

    // Validate product
    $orderProduct = OrderProduct::find($product);
    if (!$orderProduct) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid product ID.',
        ], 404);
    }

    // Validate access
    if (!ExtensionHelper::hasAccess($orderProduct, $request->user())) {
        return response()->json([
            'status' => 'error',
            'message' => 'You do not have access to this product.',
        ], 403);
    }

    // Get path from query and sanitize it
    $filePath = $request->query('path', '/'); // Default to root if not set
    $filePath = preg_replace('/\/+/', '/', $filePath); // Remove duplicate slashes

    // Ensure proper formatting (avoid leading double slashes)
    if ($filePath !== '/' && str_starts_with($filePath, '/')) {
        $filePath = substr($filePath, 1); // Remove leading slash for compatibility
    }

    // API URL (updated to match frontend)
    $url = "https://bill.momentmc.com/api/client/v1/ptero/download?file=" . urlencode($filePath) . "&invoice_id={$invoiceId}&nocache=" . time();

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apikey,
            'Content-Type'  => 'application/json',
        ])->timeout(60)->get($url);

        // Debugging log
        \Log::info('API Response: ' . $response->body());

        if ($response->successful()) {
            $data = json_decode($response->body(), true);

            if (isset($data['attributes']['url'])) {
                $signedUrl = $data['attributes']['url'];

                return redirect()->away($signedUrl);
            } else {
                throw new Exception('Signed URL not found in response');
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch file list from API.',
            ], 500);
        }
    } catch (Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Unexpected error: ' . $e->getMessage(),
        ], 500);
    }
}

public function editFile($product, $invoiceId)
{
    $request = request();

    // Validate product
    $orderProduct = OrderProduct::find($product);
    if (!$orderProduct) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid product ID.',
        ], 404);
    }

    // Validate access
    if (!ExtensionHelper::hasAccess($orderProduct, $request->user())) {
        return response()->json([
            'status' => 'error',
            'message' => 'You do not have access to this product.',
        ], 403);
    }

    // Get path from query and sanitize it
    $filePath = $request->query('path', '/'); // Default to root if not set
    $filePath = preg_replace('/\/+/', '/', $filePath); // Remove duplicate slashes

    if ($filePath !== '/' && str_starts_with($filePath, '/')) {
        $filePath = substr($filePath, 1); // Remove leading slash for compatibility
    }

    // API URL
    $url = "https://bill.momentmc.com/api/client/v1/ptero/edit";

    try {
        // Corrected request to use JSON body instead of query parameters
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apikey,
            'Content-Type'  => 'application/json',
        ])->timeout(60)->get($url, [
            'invoice_id' => $invoiceId,
            'file'       => $filePath,
        ]);

        \Log::info('API Response: ' . $response->body());

        if ($response->successful()) {
            $data = json_decode($response->body(), true);

            if (isset($data['response_body'])) {
                $fileContent = $data['response_body']; // Extract file content
                
                // Generate link (if applicable)
        $link = $this->getLink('','','',$product);
                
                return view('Momentmcreseller::editer', [
                    'filePath'     => $filePath,
                    'content'      => $fileContent,
                    'orderProduct' => $product,
                    'title'        => 'Edit files',
                    'clients'      => 1,
                    'link'         => $link
                ]);
            } else {
                throw new Exception('File content not found in response');
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch file from API.',
            ], 500);
        }
    } catch (Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Unexpected error: ' . $e->getMessage(),
        ], 500);
    }
}

public function save($product, $invoiceId)
{
    $request = request();

    // Validate product
    $orderProduct = OrderProduct::find($product);
    if (!$orderProduct) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Invalid product ID.',
        ], 404);
    }

    // Validate access
    if (!ExtensionHelper::hasAccess($orderProduct, $request->user())) {
        return response()->json([
            'status'  => 'error',
            'message' => 'You do not have access to this product.',
        ], 403);
    }

    // Get file path from request input (not query)
    $filePath = $request->input('file', ''); // Ensure it comes from the body
    $filePath = preg_replace('/\/+/', '/', $filePath); // Normalize slashes

    // Get content
    $content = $request->input('content', '');

    // API URL
    $url = "https://bill.momentmc.com/api/client/v1/ptero/save";

    try {
        // Send as JSON instead of raw text
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apikey,
            'Content-Type'  => 'application/json',
        ])->post($url, [
            'invoice_id' => $invoiceId,
            'file' => $filePath,
            'content' => $content
        ]);

        // Log request for debugging
        \Log::info('API Request:', [
            'invoice_id' => $invoiceId,
            'file' => $filePath,
            'content' => $content,
            'response' => $response->body()
        ]);

        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'File saved successfully.']);
        }

        \Log::error('Error saving file: ' . $response->body());
        return response()->json(['status' => 'error', 'message' => 'Error saving file.'], 400);
    } catch (Exception $e) {
        \Log::error('Unexpected error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Unexpected error: ' . $e->getMessage(),
        ], 500);
    }
}

public function delete($product, $invoiceId)
{
    $request = request();

    // Validate product
    $orderProduct = OrderProduct::find($product);
    if (!$orderProduct) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Invalid product ID.',
        ], 404);
    }

    // Validate access
    if (!ExtensionHelper::hasAccess($orderProduct, $request->user())) {
        return response()->json([
            'status'  => 'error',
            'message' => 'You do not have access to this product.',
        ], 403);
    }

    // Get file path from request input (not query)
    $filePath = $request->input('file', ''); // Ensure it comes from the body
    $filePath = preg_replace('/\/+/', '/', $filePath); // Normalize slashes

    // API URL
    $url = "https://bill.momentmc.com/api/client/v1/ptero/delete";

    try {
        // Send as JSON instead of raw text
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apikey,
            'Content-Type'  => 'application/json',
        ])->post($url, [
            'invoice_id' => $invoiceId,
            'file' => $filePath
        ]);

        // Log request for debugging
        \Log::info('API Request:', [
            'invoice_id' => $invoiceId,
            'file' => $filePath,
            'response' => $response->body()
        ]);

        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'File deleted successfully.']);
        }

        \Log::error('Error deleting file: ' . $response->body());
        return response()->json(['status' => 'error', 'message' => 'Error deleting file.'], 400);
    } catch (Exception $e) {
        \Log::error('Unexpected error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Unexpected error: ' . $e->getMessage(),
        ], 500);
    }
}


}


