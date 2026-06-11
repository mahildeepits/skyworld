@php
$route = 'agentcategories.store';
$method = 'POST';
    if(isset($category)){
        $route = ['agentcategories.update',encrypt($category->id)];
        $method = 'PUT';
    }
@endphp
<style>
    .custom-form-input {
        border-radius: 8px !important;
        height: 36px !important;
        font-size: 0.85rem !important;
        border: 1px solid #ced4da !important;
    }
    .custom-form-input:focus {
        border-color: #034bb3 !important;
        box-shadow: 0 0 0 0.2rem rgba(3, 75, 179, 0.25) !important;
    }
    .form-group label {
        font-weight: 600;
        margin-bottom: 2px;
        font-size: 0.8rem;
        color: #4a4a4a;
    }
    .section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #034bb3;
        margin-top: 8px;
        margin-bottom: 8px;
        padding-bottom: 3px;
        border-bottom: 2px solid #f0f0f0;
    }
</style>

{!! Form::open(['route'=> $route,'files'=>true,'method' => 'POST','onsubmit="ajaxFormSubmit($(this))"']) !!}
    @method($method)
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('name','Name') !!} <span class="text-danger">*</span>
                {!! Form::text('name',$category?->name ?? null,['class'=>'form-control custom-form-input','id' => 'name', 'placeholder' => 'Enter category name']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('image','Category Image') !!}
                <input type="file" name="image" class="form-control custom-form-input" accept="image/*" id="image">
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('unlock_balance','Wallet Balance') !!}
                {!! Form::number('unlock_balance',$category?->unlock_balance ?? null,['class'=>'form-control custom-form-input','id' => 'unlock_balance', 'placeholder' => '0.00']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('required_points','Required Points') !!}
                {!! Form::number('required_points',$category?->required_points ?? null,['class'=>'form-control custom-form-input','id' => 'required_points', 'placeholder' => '0']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('massive_order_rate','ROI ( % )') !!}
                {!! Form::number('massive_order_rate',$category?->massive_order_rate ?? null,['class'=>'form-control custom-form-input','id' => 'massive_order_rate', 'placeholder' => '0', 'step' => '0.01']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('level_upgrade_income','Level Upgrade Income') !!}
                {!! Form::number('level_upgrade_income',$category?->level_upgrade_income ?? null,['class'=>'form-control custom-form-input','id' => 'level_upgrade_income', 'placeholder' => '0.00', 'step' => '0.01']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="section-title">Team Requirements</div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('team_a','Team A (Direct Users)') !!}
                {!! Form::number('team_a',$category?->team_a ?? null,['class'=>'form-control custom-form-input','id' => 'team_a', 'placeholder' => 'Min. users in Team A']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('team_b_c','Team B & C (Total Users)') !!}
                {!! Form::number('team_b_c',$category?->team_b_c ?? null,['class'=>'form-control custom-form-input','id' => 'team_b_c', 'placeholder' => 'Total users in Team B & C']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="section-title">Team Profit ( % )</div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('team_a_profit','Team A') !!}
                {!! Form::number('team_a_profit',$category?->team_a_profit ?? null,['class'=>'form-control custom-form-input','id' => 'team_a_profit', 'placeholder' => '0.00', 'step' => '0.01']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('team_b_profit','Team B') !!}
                {!! Form::number('team_b_profit',$category?->team_b_profit ?? null,['class'=>'form-control custom-form-input','id' => 'team_b_profit', 'placeholder' => '0.00', 'step' => '0.01']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('team_c_profit','Team C') !!}
                {!! Form::number('team_c_profit',$category?->team_c_profit ?? null,['class'=>'form-control custom-form-input','id' => 'team_c_profit', 'placeholder' => '0.00', 'step' => '0.01']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-12 pt-3 text-right">
            <button type="button" class="btn btn-secondary rounded-pill px-3 py-2 mr-2" data-bs-dismiss="modal">Cancel</button>
            {!! Form::submit('Save Category',['class'=>'btn btn-main rounded-pill px-3 py-2']) !!}
        </div>
    </div>
{!! Form::close() !!}