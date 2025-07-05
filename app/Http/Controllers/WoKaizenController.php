<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role; // Pastikan model Role diimpor
use App\Models\User;
use App\Models\Sites;
use App\Models\Department;
use App\Models\Kaizen;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB; // Add this line

class WoKaizenController extends Controller
{
    protected $currentUser; // Properti untuk menyimpan user yang sedang login

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = Auth::user(); // Mengambil user yang sedang login
            return $next($request);
        });
    }

    // public function index()
    // {
    //     $workOrders = WorkOrder::with(['site', 'department', 'kaizen'])
    //         ->orderBy('wo_id', 'desc')
    //         ->whereNot('approved', '1')
    //         ->paginate(10);
            
    //     // Get all kaizen_ids from the work orders
    //     $kaizenIds = $workOrders->pluck('wo_id')->unique()->toArray();
        
    //     // Get all attachments for these kaizens
    //     $attachments = DB::table('work_order_progress_attachment')
    //                     ->whereIn('wo_id', $kaizenIds)
    //                     ->get()
    //                     ->groupBy('wo_id');
            
    //     $kaizens = Kaizen::orderBy('kaizen_id', 'desc')->get();
            
    //     return view('pages.kaizen.wo.index', compact('workOrders', 'kaizens', 'attachments'));
    // }
    
    public function index()
{
    
    //  $selectedBranchId = session('selected_branch', 'all');
     
    // $workOrders = WorkOrder::with(['site', 'department', 'kaizen'])
    //     ->orderBy('wo_id', 'desc')
    //     ->whereNot('approved', '1')
    //     ->paginate(10);
    
    $selectedBranchId = session('selected_branch', 'all');

    $workOrders = WorkOrder::with(['site', 'department', 'kaizen'])
        ->orderBy('wo_id', 'desc')
        ->where('approved', '!=', 1)
        ->when($selectedBranchId !== 'all', function($query) use ($selectedBranchId) {
            // Join dengan table sites untuk filter by branch_id
            $query->whereHas('site', function($q) use ($selectedBranchId) {
                $q->where('site_id', $selectedBranchId);
            });
        })
        ->paginate(10);
    
    $kaizenIds = $workOrders->pluck('wo_id')->unique()->toArray();
    
    // Get all attachments
    $attachments = DB::table('work_order_progress_attachment')
                    ->whereIn('wo_id', $kaizenIds)
                    ->get()
                    ->groupBy('wo_id');
    
    // Get worker data (handle both single and multiple workers)
    $workersData = DB::table('work_order')
                    //->whereIn('wo_id', $kaizenIds)
                    ->select('wo_id', 'worker')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        // Handle jika kolom worker kosong/null
                        if (empty($item->worker)) {
                            return [$item->wo_id => []];
                        }
                        
                        // Convert ke array (baik single ID maupun multiple)
                        $workerIds = is_array($item->worker) ? $item->worker : explode(',', $item->worker);
                        
                        // Get worker names
                        $workers = User::whereIn('user_id', $workerIds)
                                    ->select('user_id', 'name')
                                    ->get()
                                    ->map(function ($user) {
                                        return [
                                            'id' => $user->user_id,
                                            'name' => $user->name
                                        ];
                                    })
                                    ->toArray();
                        
                        return [$item->wo_id => $workers];
                    });
     $kaizens = Kaizen::orderBy('kaizen_id', 'desc')->get();
    
    return view('pages.kaizen.wo.index', compact('workOrders', 'kaizens', 'attachments', 'workersData'));
}

    // store lama public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'site_id' => 'required|integer',
    //         'category_id' => 'nullable|integer',
    //         'kaizen_id' => 'required|integer',
    //         'start_scheduled_date' => 'required|date',
    //         'end_scheduled_date' => 'required|date|after:start_scheduled_date',
    //         'expected_work_time' => 'required|integer',
    //         'expected_work_time2' => 'required|integer|in:0,1,2',
    //         'worker' => 'required|string|max:255',
    //         'assigned_remark' => 'required|string',
    //         'assigned_date' => 'required|date',
    //         'executed_date' => 'required|date',
    //         'finish_date' => 'required|date',
    //         'approved' => 'required|integer',
    //         'approved_date' => 'nullable|date',
    //     ]);
        
    //     //dd($validated);
    //     WorkOrder::create($validated);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Work Order created successfully'
    //     ]);
    // }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|integer',
            'category_id' => 'nullable|integer',
            'kaizen_id' => 'required|integer',
            'start_scheduled_date' => 'required|date',
            'end_scheduled_date' => 'required|date|after:start_scheduled_date',
            'expected_work_time' => 'required|integer',
            'expected_work_time2' => 'required|integer|in:0,1,2',
            'worker' => 'required|array', // Ubah ke array
            'worker.*' => 'integer', // Validasi setiap item dalam array
            'assigned_remark' => 'required|string',
            'assigned_date' => 'required|date',
            'executed_date' => 'required|date',
            'finish_date' => 'required|date',
            'approved' => 'required|integer',
            'approved_date' => 'nullable|date',
        ]);
    
        // Simpan setiap worker sebagai record terpisah
        foreach ($validated['worker'] as $workerId) {
            WorkOrder::create([
                'site_id' => $validated['site_id'],
                'category_id' => $validated['category_id'],
                'kaizen_id' => $validated['kaizen_id'],
                'start_scheduled_date' => $validated['start_scheduled_date'],
                'end_scheduled_date' => $validated['end_scheduled_date'],
                'expected_work_time' => $validated['expected_work_time'],
                'expected_work_time2' => $validated['expected_work_time2'],
                'worker' => $workerId, // Simpan worker individual
                'assigned_remark' => $validated['assigned_remark'],
                'assigned_date' => $validated['assigned_date'],
                'executed_date' => $validated['executed_date'],
                'finish_date' => $validated['finish_date'],
                'approved' => $validated['approved'],
                'approved_date' => $validated['approved_date'],
            ]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Work Order created successfully'
        ]);
    }

    // public function edit($id)
    // {
    //     $workOrder = WorkOrder::findOrFail($id);
    //  return response()->json($workOrder);
    // }
    
    public function edit($id)
    {
            $workOrder = WorkOrder::with('user:user_id,name')->findOrFail($id);
            
            return response()->json([
            'wo_id' => $workOrder->wo_id,
            'site_id' => $workOrder->site_id,
            'worker' => $workOrder->worker, // ID worker
            'worker_name' => $workOrder->user->name ?? 'Unknown', // Nama worker
            'kaizen_id' => $workOrder->kaizen_id,
            'start_scheduled_date' => $workOrder->start_scheduled_date,
            'end_scheduled_date' => $workOrder->end_scheduled_date,
            'expected_work_time' => $workOrder->expected_work_time,
            'expected_work_time2' => $workOrder->expected_work_time2, // 0=min, 1=hour, 2=day
            'assigned_remark' => $workOrder->assigned_remark,
            'approved' => $workOrder->approved,
            // Tambahkan field lain sesuai kebutuhan form
        ]);
    }

   public function update(Request $request, $id)
{
    // Temukan work order yang akan diupdate
    $workOrder = WorkOrder::findOrFail($id);
    
    // Validasi hanya field yang ada di form edit
    $validated = $request->validate([
        'site_id' => 'required|integer|exists:sites,site_id',
        'worker' => 'required|array',
        'start_scheduled_date' => 'required|date',
        'end_scheduled_date' => 'required|date|after:start_scheduled_date',
        'expected_work_time' => 'required|numeric|min:0',
        'expected_work_time2' => 'required|integer|in:0,1,2', // 0=minutes, 1=hours, 2=days
        'assigned_remark' => 'required|string|max:500',
        'approved' => 'integer',
        
    ]);
    
    // Ubah array worker menjadi string yang dipisahkan koma
    $validatedData['worker'] = implode(',', $validated['worker']);
    
    //dd($validated);
    
    // Siapkan data untuk diupdate
    $updateData = [
        // Field yang diubah (dari form)
        'site_id' => $validated['site_id'],
        'worker' => $validatedData['worker'],
        'start_scheduled_date' => $validated['start_scheduled_date'],
        'end_scheduled_date' => $validated['end_scheduled_date'],
        'expected_work_time' => $validated['expected_work_time'],
        'expected_work_time2' => $validated['expected_work_time2'],
        'assigned_remark' => $validated['assigned_remark'],
        'assigned_date' => now(), // Update timestamp
        'approved' => $validated['approved'],
        'approved_date' => now(),
        
        
        // Field yang tidak diubah (dipertahankan dari data sebelumnya)
        'department_id' => $workOrder->department_id,
        'area_id' => $workOrder->area_id,
        'location' => $workOrder->location,
        'kaizen_type_id' => $workOrder->kaizen_type_id,
        'kaizen_id' => $workOrder->kaizen_id,
    ];
    
    // Handle approved status jika ada
    if (isset($validated['approved'])) {
        $updateData['approved'] = $validated['approved'];
        $updateData['approved_date'] = $validated['approved'] ? now() : null;
    }
    
    try {
        // Lakukan update
        $workOrder->update($updateData);
        
        return response()->json([
            'success' => true,
            'message' => 'Work Order updated successfully',
            'data' => $workOrder
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update Work Order',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        WorkOrder::findOrFail($id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Work Order deleted successfully'
        ]);
    }

    // public function deleteWoKaizen(Request $request)
    // {
    //     $ids = $request->ids;
    //     WorkOrder::whereIn('wo_id', $ids)->delete();
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Selected Work Orders deleted successfully'
    //     ]);
    // }
    
    public function deleteWoKaizen(Request $request)
    {
        $ids = $request->ids;
        
        try {
            DB::transaction(function () use ($ids) {
                // 1. Hapus attachment files terlebih dahulu
                $attachments = DB::table('work_order_progress_attachment')
                              ->whereIn('wo_id', $ids)
                              ->get();
                
                // Delete physical files
                foreach ($attachments as $attachment) {
                    if (file_exists(public_path($attachment->filename))) {
                        unlink(public_path($attachment->filename));
                    }
                }
                
                // 2. Hapus record dari tabel-tabel terkait
                DB::table('work_order_comments')
                    ->whereIn('wo_id', $ids)
                    ->delete();
                    
                DB::table('work_order_progress_attachment')
                    ->whereIn('wo_id', $ids)
                    ->delete();
                    
                // 3. Hapus dari tabel utama
                WorkOrder::whereIn('wo_id', $ids)->delete();
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Selected Work Orders and all related data deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // app/Http/Controllers/KaizenController.php
    public function getDetails($id)
    {
        $kaizen = Kaizen::with(['department', 'site'])->findOrFail($id);
        
        return response()->json([
            'department_id' => $kaizen->department_id,
            'department_name' => $kaizen->department->department_name ?? 'N/A',
            'site_id' => $kaizen->site_id,
            'name_site' => $kaizen->site->name_site ?? 'N/A',
            'kaizen_data' => $kaizen
        ]);
    }
    
     public function getWorkers(Request $request)
    {
        $siteId = $request->input('site_id');
        $workers = User::where('site_id', $siteId)
                     ->select('site_id', 'name', 'user_id')
                     ->get();
                     
        return response()->json($workers);
    }
    
    
    
    public function getWorker($id)
    {
        // Ambil data worker berdasarkan ID dari kolom worker di work_orders
        $worker = User::find($id, ['user_id', 'name']);
        
        if (!$worker) {
            return response()->json([
                'success' => false,
                'message' => 'Worker not found'
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'data' => $worker
        ]);
    }

}