<form action="{{route('admin.setpaid.function',$user->id)}}" method="post" onsubmit="ajaxFormSubmit($(this))" >
    @csrf
    <div class="form-group">
        <label for="amount">USDT</label>
        <input type="number" name="amount" id="amount" class="form-control" required />
    </div>
    <div class="mt-3">
        <input type="submit" value="Submit" name="submit" class="btn btn-primary">
    </div>
</form>