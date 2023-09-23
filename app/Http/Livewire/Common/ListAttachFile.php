<?php

namespace App\Http\Livewire\Common;

use App\Enums\EApiApproval;
use App\Models\Budget;
use App\Models\RequestLeave;
use App\Models\ResearchPlan;
use App\Models\Topic;
use App\Models\TopicFee;
use App\Models\WorkPlan;
use Livewire\Component;

class ListAttachFile extends Component
{
    public $type;
    public $model_id;

    protected $listeners = ['saveDataSendApproval'];

    public function mount($modelId, $type) {
        $this->model_id = $modelId;
        $this->type = $type;
    }

    public function render()
    {
        $listFile = null;
        if ($this->type == EApiApproval::TYPE_IDEAL) {

        } elseif ($this->type == EApiApproval::TYPE_TOPIC) {
            $topic = Topic::find($this->model_id);
            $listFile = !empty($topic->gw_attach_file) ? json_decode($topic->gw_attach_file, true) : null;
        } elseif ($this->type == EApiApproval::TYPE_COST) {
            $topicFee = TopicFee::find($this->model_id);
            $listFile = !empty($topicFee->gw_attach_file) ? json_decode($topicFee->gw_attach_file, true) : null;
        } elseif ($this->type == EApiApproval::TYPE_RESEARCH_PLAN) {
            $researchPlan = ResearchPlan::find($this->model_id);
            $listFile = !empty($researchPlan->gw_attach_file) ? json_decode($researchPlan->gw_attach_file, true) : null;
        } elseif ($this->type == EApiApproval::TYPE_WORK_PLAN) {
            $workPlan = WorkPlan::find($this->model_id);
            $listFile = !empty($workPlan->gw_attach_file) ? json_decode($workPlan->gw_attach_file, true) : null;
        } elseif ($this->type == EApiApproval::TYPE_BUDGET) {
            $budget = Budget::find($this->model_id);
            $listFile = !empty($budget->gw_attach_file) ? json_decode($budget->gw_attach_file, true) : null;
        } elseif ($this->type == EApiApproval::TYPE_REQUEST_LEAVE) {
            $requestLeave = RequestLeave::find($this->model_id);
            $listFile = !empty($requestLeave->gw_attach_file) ? json_decode($requestLeave->gw_attach_file, true) : null;
        }

        return view('livewire.common.list-attach-file', compact('listFile'));
    }
}
