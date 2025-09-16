<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalChain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'rules',
        'created_by'
    ];

    protected $casts = [
        'rules' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function steps()
    {
        return $this->hasMany(ApprovalStep::class)->orderBy('order');
    }

    public function fuelRequests()
    {
        return $this->hasMany(FuelRequest::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Methods
    public function getNextStep($currentStep = null)
    {
        if ($currentStep) {
            return $this->steps()
                ->where('order', '>', $currentStep->order)
                ->orderBy('order')
                ->first();
        }

        return $this->steps()->orderBy('order')->first();
    }

    public function getFirstStep()
    {
        return $this->steps()->orderBy('order')->first();
    }

    public function getLastStep()
    {
        return $this->steps()->orderBy('order', 'desc')->first();
    }

    public function canApprove($user, $fuelRequest)
    {
        $currentStep = $this->getCurrentStep($fuelRequest);

        if (!$currentStep) {
            return false;
        }

        return $currentStep->canApprove($user, $fuelRequest);
    }

    public function getCurrentStep($fuelRequest)
    {
        $currentStepId = $fuelRequest->current_approval_step_id;

        if ($currentStepId) {
            return $this->steps()->find($currentStepId);
        }

        return $this->getFirstStep();
    }

    public function moveToNextStep($fuelRequest)
    {
        $currentStep = $this->getCurrentStep($fuelRequest);
        $nextStep = $this->getNextStep($currentStep);

        if ($nextStep) {
            $fuelRequest->update(['current_approval_step_id' => $nextStep->id]);
            return $nextStep;
        } else {
            // No more steps, request is fully approved
            $fuelRequest->update([
                'status' => FuelRequest::STATUS_APPROVED,
                'current_approval_step_id' => null
            ]);
            return null;
        }
    }

    public function evaluateRules($fuelRequest)
    {
        foreach ($this->rules as $rule) {
            if ($this->evaluateRule($rule, $fuelRequest)) {
                return $rule['chain_id'] ?? $this->id;
            }
        }

        return $this->id;
    }

    private function evaluateRule($rule, $fuelRequest)
    {
        switch ($rule['type']) {
            case 'amount':
                return $fuelRequest->total_amount >= $rule['min_amount'];
            case 'client_type':
                return $fuelRequest->client->company_name === $rule['client_name'];
            case 'fuel_type':
                return $fuelRequest->fuel_type === $rule['fuel_type'];
            case 'station':
                return $fuelRequest->station_id == $rule['station_id'];
            case 'urgency':
                return $fuelRequest->urgency_level === $rule['urgency_level'];
            default:
                return false;
        }
    }
}
