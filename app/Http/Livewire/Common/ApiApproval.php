<?php

namespace App\Http\Livewire\Common;

use App\Enums\EApiApproval;
use App\Enums\EWorkPlanPriorityLevel;
use App\Models\Approval;
use App\Models\ApprovalIdealDetail;
use App\Models\Budget;
use App\Models\BudgetHasExpensePlan;
use App\Models\GwApproveResponse;
use App\Models\Menu;
use App\Models\RequestLeave;
use App\Models\ResearchPlan;
use App\Models\Task;
use App\Models\Topic;
use App\Models\TopicFee;
use App\Models\TopicFeeDetail;
use App\Models\UserHasMenu;
use App\Models\WorkPlan;
use App\Models\WorkPlanHasUserInfo;
use App\Service\UserHasMenuService;
use Livewire\Component;

class ApiApproval extends Component
{
    public $token;
    public $url;
    public $subject;
    public $content;
    public $type;
    public $request_id;
    public $model_id;
    public $model_name;
    public $approvalId;
    public $name;
    public $idModelDraft;
    public $openTable = "<table><thead><tr>";
    public $closeTable = "</tbody></table>";
    public $openBody = "</tr></thead><tbody>";

    protected $listeners = ['saveDataSendApproval'];

    public function mount($modelId, $type, $requestId = null, $approvalId = null, $idModelDraft = null) {
        $this->model_id = $modelId;
        $this->type = $type;
        $this->approvalId = $approvalId;
        $this->request_id =  $requestId;
        $this->idModelDraft =  $idModelDraft;
    }

    public function render()
    {
        $dataRequest = getDataRequest();
        if (!$dataRequest['success']) {
            return false;
        }
        $this->token = $dataRequest['token'];
        $domain = request()->getSchemeAndHttpHost();


        if ($this->type == EApiApproval::TYPE_IDEAL) {
            $approval = Approval::findOrFail($this->approvalId);
            $this->name = $approval->name;
            $this->subject = $approval->name;
            $this->content = $this->renderContentIdeal();
        } elseif ($this->type == EApiApproval::TYPE_TOPIC) {
            $this->model_name = 'App\Models\Topic';
            $topic = Topic::findOrFail($this->model_id);
            $this->content = $this->renderContentTopic($topic);
            $approval = Approval::findOrFail($this->approvalId);
            $this->subject = $approval->name;
            $this->name = $approval->name;
        } elseif ($this->type == EApiApproval::TYPE_COST) {
            $this->model_name = 'App\Models\TopicFee';
            $topicFee = TopicFee::findOrFail($this->model_id);
            $this->content = $this->renderContentTopicFee($topicFee);
            $approval = Approval::findOrFail($this->approvalId);
            $this->subject = $approval->name;
            $this->name = $approval->name;
        } elseif ($this->type == EApiApproval::TYPE_RESEARCH_PLAN) {
            $this->model_name = 'App\Models\ResearchPlan';
            $researchPlan = ResearchPlan::with('topic')->findOrFail($this->model_id);
            $this->content = $this->renderContentResearchPlan($researchPlan);
            $approval = Approval::findOrFail($this->approvalId);
            $this->subject = $approval->name;
            $this->name = $approval->name;
        } elseif ($this->type == EApiApproval::TYPE_WORK_PLAN) {
            $this->model_name = 'App\Models\WorkPlan';
            $workPlan = WorkPlan::findOrFail($this->model_id);
            $this->content = $this->renderContentWorkPlan($workPlan);
            $this->subject = $workPlan->content;
            $this->name = $workPlan->content;
        } elseif ($this->type == EApiApproval::TYPE_BUDGET) {
            $this->model_name = 'App\Models\Budget';
            $budget = Budget::findOrFail($this->model_id);
            $this->content = $this->renderContentBudget($budget);
            $this->subject = $budget->name;
            $this->name = $budget->name;
        } elseif ($this->type == EApiApproval::TYPE_REQUEST_LEAVE) {
            $this->model_name = 'App\Models\RequestLeave';
            $requestLeave = RequestLeave::with('admin', 'admin.info')->findOrFail($this->model_id);
            $this->content = $this->renderContentRequestLeave($requestLeave);
            $this->subject = __('executive/my-day-off.apply_for_leave');
            $this->name = __('executive/my-day-off.apply_for_leave');
        }

        $this->url = $domain.'/api/approve?model_id=' . $this->model_id . '&request_id=' . $this->request_id
                    . '&type=' . $this->type . '&approval_id=' . $this->approvalId;

        $urlApproval = config('common.url_gw.approval');

        return view('livewire.common.api-approval', compact('urlApproval'));
    }

    public function saveDataSendApproval()
    {
        GwApproveResponse::create([
            'name' => $this->name,
            'request_id' => $this->request_id,
            'admin_id' => auth()->id(),
            'model_name' => $this->model_name,
            'model_id' => $this->model_id,
        ]);

        $this->emit('submit-form-approval');
    }

    public function renderContentTopic($topic)
    {
        $html = '<p>' . __('data_field_name.topic.name') . ':' .  $topic->name . '</p>';
        $html .= '<p>' . __('data_field_name.topic.code') . ':' .  $topic->code . '</p>';
        $html .= '<p>' . __('data_field_name.topic.necessary') . ':' .  $topic->necessary . '</p>';
        $html .= '<p>' . __('data_field_name.topic.overview') . ':' .  $topic->overview . '</p>';
        $html .= '<p>' . __('data_field_name.topic.target') . ':' .  $topic->target . '</p>';
        $html .= '<p>' . __('data_field_name.topic.start_date') . ':' .  $topic->start_date . '</p>';
        $html .= '<p>' . __('data_field_name.topic.end_date') . ':' .  $topic->end_date . '</p>';
        $html .= '<p>' . __('data_field_name.topic.expected_fee') . ':' .  $topic->expected_fee . '</p>';
        return $html;
    }

    public function renderContentTopicFee($topicFee)
    {
        $html = '<p>' . __('data_field_name.research_cost.name_topic_fee') . ':' .  $topicFee->name . '</p>';

        $details = TopicFeeDetail::where('topic_fee_id', $topicFee->id)->get();
        if ($details->isNotEmpty()) {
            $html .= $this->openTable;
            $html .= "<th>" . __('data_field_name.common_field.code') . "</th>";
            $html .= "<th>" . __('data_field_name.research_cost.content_expenses') . "</th>";
            $html .= "<th>" . __('data_field_name.research_cost.total_capital') . "</th>";
            $html .= "<th>" . __('data_field_name.research_cost.state_capital') . "</th>";
            $html .= "<th>" . __('data_field_name.research_cost.other_capital') . "</th>";
            $html .= $this->openBody;
            foreach ($details as $row) {
                $html .= "<tr> <td>" . $row->id . "</td>";
                $html .= "<td>" . $row->name . "</td>";
                $html .= "<td>" . numberFormat($row->total_capital) ?? '' . "</td>";
                $html .= "<td>" . numberFormat($row->state_capital) ?? '' . "</td>";
                $html .= "<td>" . numberFormat($row->other_capital) ?? '' . "</td>";
                $html .= "</tr>";
            }
            $html .= $this->closeTable;
        }

        return $html;
    }

    public function renderContentIdeal()
    {
        $html = null;
        $ideals = ApprovalIdealDetail::with('ideal', 'ideal.researchField')->where('approval_id', $this->approvalId)->get();
        if ($ideals->isNotEmpty()) {
            $html .= $this->openTable;
            $html .= "<th>" . __('data_field_name.common_field.code') . "</th>";
            $html .= "<th>" . __('data_field_name.ideal.name') . "</th>";
            $html .= "<th>" . __('data_field_name.ideal.research_field') . "</th>";
            $html .= "<th>" . __('data_field_name.ideal.content') . "</th>";
            $html .= "<th>" . __('data_field_name.ideal.result') . "</th>";
            $html .= "<th>" . __('data_field_name.ideal.execution_time') . "</th>";
            $html .= "<th>" . __('data_field_name.ideal.fee') . "</th>";
            $html .= $this->openBody;
            foreach ($ideals as $row) {
                $html .= "<tr> <td>" . $row->ideal->code ?? null . "</td>";
                $html .= "<td>" . $row->ideal->name ?? null . "</td>";
                $html .= "<td>" . $row->ideal->researchField->name ?? null . "</td>";
                $html .= "<td>" . $row->ideal->content ?? null . "</td>";
                $html .= "<td>" . $row->ideal->result ?? null . "</td>";
                $html .= "<td>" . $row->ideal->execution_time ?? null . "</td>";
                $html .= "<td>" . numberFormat($row->ideal->fee) ?? null . "</td>";
                $html .= "</tr>";
            }
            $html .= $this->closeTable;
        }

        return $html;
    }

    public function renderContentResearchPlan($researchPlan)
    {
        $html = '<p>' . __('data_field_name.research_plan.research_plan_name') . ':' .  $researchPlan->name . '</p>';
        $html .= '<p>' . __('data_field_name.topic.code') . ':' .  $researchPlan->topic->code ?? null . '</p>';
        $html .= '<p>' . __('data_field_name.research_plan.start_time') . ':' .  $researchPlan->star_date . '</p>';
        $html .= '<p>' . __('data_field_name.research_plan.end_time') . ':' .  $researchPlan->end_date . '</p>';

        $tasks = Task::with('userInfo')->where('research_plan_id', $researchPlan->id)->get();
        if ($tasks->isNotEmpty()) {
            $html .= $this->openTable;
            $html .= "<th>" . __('data_field_name.research_plan.task_name') . "</th>";
            $html .= "<th>" . __('data_field_name.research_plan.created_at') . "</th>";
            $html .= "<th>" . __('data_field_name.research_plan.start_time') . "</th>";
            $html .= "<th>" . __('data_field_name.research_plan.end_time') . "</th>";
            $html .= $this->openBody;
            foreach ($tasks as $task) {
                $html .= "<tr> <td>" . $task->name . "</td>";
                $html .= "<td>" . $task->userInfo->fullname ?? null . "</td>";
                $html .= "<td>" . reFormatDate($task->start_date, 'd/m/Y') . "</td>";
                $html .= "<td>" . reFormatDate($task->end_date, 'd/m/Y') . "</td>";
                $html .= "</tr>";
            }
            $html .= $this->closeTable;
        }

        return $html;
    }

    public function renderContentWorkPlan($workPlan)
    {
        $priorityList = $this->getPriorityList();
        $budgetList = $this->getBudgetList();
        // $budgets = Budget::withTrashed()->get();
        $priority_level = $workPlan->priority_level != null ? $priorityList[$workPlan->priority_level] : '';
        $budget_name = !empty($workPlan->budget_id) ? $budgetList[$workPlan->budget_id] : '';

        $html = '<p>' . __('data_field_name.work_plan.index.mission_id') . ':' .  $workPlan->missions_code . '</p>';
        $html .= '<p>' . __('data_field_name.work_plan.create.content') . ':' .  $workPlan->content . '</p>';
        $html .= '<p>' . __('data_field_name.work_plan.index.missions') . ':' .  $workPlan->missions . '</p>';
        $html .= '<p>' . __('data_field_name.work_plan.create.start_time') . ':' .  reFormatDate($workPlan->start_date, 'd/m/Y') . '</p>';
        $html .= '<p>' . __('data_field_name.work_plan.create.end_time') . ':' .  reFormatDate($workPlan->end_date, 'd/m/Y') . '</p>';
        $html .= '<p>' . __('data_field_name.work_plan.create.fee_type') . ':' .  $budget_name . '</p>';
        $html .= '<p>' . __('data_field_name.work_plan.create.fee') . ':' .  numberFormat($workPlan->estimated_cost) . '</p>';
        $html .= '<p>' . __('data_field_name.work_plan.index.priority1') . ':' .  $priority_level . '</p>';

        $works = WorkPlanHasUserInfo::with('userInfo')->where('work_plan_id', $workPlan->id)->get();
        if ($works->isNotEmpty()) {
            $html .= $this->openTable;
            $html .= "<th>" . __('data_field_name.work_plan.create.name') . "</th>";
            $html .= "<th>" . __('data_field_name.work_plan.edit.date') . "</th>";
            $html .= "<th>" . __('data_field_name.common_field.content') . "</th>";
            $html .= $this->openBody;
            foreach ($works as $work) {
                $html .= "<tr> <td>" . $work->userInfo->fullname ?? null . "</td>";
                $html .= "<td>" . reFormatDate($work->working_day, 'd/m/Y') . "</td>";
                $html .= "<td>" . $work->content . "</td>";
                $html .= "</tr>";
            }
            $html .= $this->closeTable;
        }

        return $html;
    }

    public function renderContentBudget($budget)
    {
        $total_budget = $budget->total_budget;
        $arrMoneyPlan = null;
        if ($this->idModelDraft != null) {
            $data_draft = $budget->request_gw_data;

            if (!empty($data_draft)) {
                if ($budget->allocated == 1){
                    $decode = json_decode($data_draft);

                    $total_budget = $decode->total_budget;
                    $money_plan = $decode->money_plan;
                    $arrMoneyPlan = explode(" ", $money_plan);
                }else{
                    $decode = json_decode($data_draft);

                    $total_budget = $decode->total_budget;
                }

            }
        }

        $html = '<p>' . __('data_field_name.budget.code') . ':' .  $budget->code . '</p>';
        $html .= '<p>' . __('data_field_name.budget.name') . ':' .  $budget->name . '</p>';
        $html .= '<p>' . __('data_field_name.budget.total') . ':' .  numberFormat($total_budget) . '</p>';
        $html .= '<p>' . __('data_field_name.budget.content') . ':' .  $budget->content . '</p>';
        $html .= '<p>' . __('data_field_name.budget.year') . ':' .  $budget->year_created . '</p>';
        $html .= '<p>' . __('data_field_name.budget.note') . ':' .  $budget->note . '</p>';

        if (!empty($arrMoneyPlan) && $this->idModelDraft != null) {
            $html .= $this->openTable;
            $html .= "<th>" . __('data_field_name.budget.month') . "</th>";
            $html .= "<th>" . __('data_field_name.budget.detail') . "</th>";
            $html .= $this->openBody;
            foreach ($arrMoneyPlan as $key => $moneyPlan) {
                $month = $key + 1;
                $html .= "<tr> <td>" . __('data_field_name.budget.month') . $month . "</td>";
                $html .= "<td>" . $moneyPlan . "</td>";
                $html .= "</tr>";
            }
            $html .= $this->closeTable;
        } else {
            $budgets = BudgetHasExpensePlan::where('budget_id', $budget->id)->get();
            if ($budgets->isNotEmpty()) {
                $html .= $this->openTable;
                $html .= "<th>" . __('data_field_name.budget.month') . "</th>";
                $html .= "<th>" . __('data_field_name.budget.detail') . "</th>";
                $html .= $this->openBody;
                foreach ($budgets as $budget) {
                    $html .= "<tr> <td>" . __('data_field_name.budget.month') . $budget->month_budget . "</td>";
                    $html .= "<td>" . numberFormat($budget->money_plan) . "</td>";
                    $html .= "</tr>";
                }
                $html .= $this->closeTable;
            }
        }
        return $html;
    }

    public function renderContentRequestLeave($requestLeave)
    {
        $fullname = $requestLeave->admin->info->fullname ?? '';
        $code = $requestLeave->admin->info->code ?? '';

        $html = '<p>' . __('data_field_name.common_field.name') . ':' .  $fullname . '</p>';
        $html .= '<p>' . __('data_field_name.user.code') . ':' .  $code . '</p>';
        $html .= '<p>' . __('executive/my-day-off.from_date') . ':' .  reFormatDate($requestLeave->start_date, 'H:i d/m/Y') ?? null . '</p>';
        $html .= '<p>' . __('executive/my-day-off.to_date') . ':' .  reFormatDate($requestLeave->end_date, 'H:i d/m/Y') ?? null . '</p>';
        $html .= '<p>' . __('executive/my-day-off.reason_for_leave') . ':' .  $requestLeave->content . '</p>';
        return $html;
    }

    protected function getPriorityList()
    {
        return [
            EWorkPlanPriorityLevel::MEDIUM => __('data_field_name.work_plan.index.medium'),
            EWorkPlanPriorityLevel::HIGH => __('data_field_name.work_plan.index.high'),
            EWorkPlanPriorityLevel::COGENCY => __('data_field_name.work_plan.index.cogency'),
        ];
    }


    protected function getBudgetList()
    {
        $budgets = Budget::withTrashed()->get();
        $budgetList = null;
        foreach ($budgets as $budget) {
            $budgetList[$budget->id] = $budget->name;
        }

        return $budgetList;
    }
}
