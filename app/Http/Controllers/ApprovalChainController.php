<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalChain;
use App\Models\ApprovalStep;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApprovalChainController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $chains = ApprovalChain::with(['steps', 'createdBy'])
            ->latest()
            ->paginate(20);

        return view('approval-chains.index', compact('chains'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STATION_MANAGER, User::ROLE_TREASURY])
            ->get();

        return view('approval-chains.create', compact('users'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'rules' => 'nullable|array',
            'steps' => 'required|array|min:1',
            'steps.*.name' => 'required|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.approver_type' => 'required|in:user,role,station_manager,admin',
            'steps.*.approver_id' => 'nullable|exists:users,id',
            'steps.*.approver_role' => 'nullable|string',
            'steps.*.is_required' => 'boolean',
            'steps.*.timeout_hours' => 'nullable|integer|min:1'
        ]);

        $chain = ApprovalChain::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? true,
            'rules' => $validated['rules'] ?? [],
            'created_by' => Auth::id()
        ]);

        // Create approval steps
        foreach ($validated['steps'] as $index => $stepData) {
            ApprovalStep::create([
                'approval_chain_id' => $chain->id,
                'name' => $stepData['name'],
                'description' => $stepData['description'],
                'order' => $index + 1,
                'approver_type' => $stepData['approver_type'],
                'approver_id' => $stepData['approver_id'] ?? null,
                'approver_role' => $stepData['approver_role'] ?? null,
                'is_required' => $stepData['is_required'] ?? true,
                'timeout_hours' => $stepData['timeout_hours'] ?? null
            ]);
        }

        return redirect()->route('approval-chains.index')
            ->with('success', 'Approval chain created successfully!');
    }

    public function show(ApprovalChain $approvalChain)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $approvalChain->load(['steps', 'createdBy', 'fuelRequests']);

        return view('approval-chains.show', compact('approvalChain'));
    }

    public function edit(ApprovalChain $approvalChain)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $approvalChain->load('steps');
        $users = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STATION_MANAGER, User::ROLE_TREASURY])
            ->get();

        return view('approval-chains.edit', compact('approvalChain', 'users'));
    }

    public function update(Request $request, ApprovalChain $approvalChain)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'rules' => 'nullable|array'
        ]);

        $approvalChain->update($validated);

        return redirect()->route('approval-chains.index')
            ->with('success', 'Approval chain updated successfully!');
    }

    public function destroy(ApprovalChain $approvalChain)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if chain is in use
        if ($approvalChain->fuelRequests()->count() > 0) {
            return redirect()->route('approval-chains.index')
                ->with('error', 'Cannot delete approval chain that is currently in use.');
        }

        $approvalChain->delete();

        return redirect()->route('approval-chains.index')
            ->with('success', 'Approval chain deleted successfully!');
    }

    public function duplicate(ApprovalChain $approvalChain)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $newChain = $approvalChain->replicate();
        $newChain->name = $approvalChain->name . ' (Copy)';
        $newChain->created_by = Auth::id();
        $newChain->save();

        // Duplicate steps
        foreach ($approvalChain->steps as $step) {
            $newStep = $step->replicate();
            $newStep->approval_chain_id = $newChain->id;
            $newStep->save();
        }

        return redirect()->route('approval-chains.index')
            ->with('success', 'Approval chain duplicated successfully!');
    }
}
