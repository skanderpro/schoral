@if (session('success_msg'))
    <div class="alert alert-success">
        <p>{!! session('success_msg') !!}</p>
    </div>
@endif


<form role="form" action="{{route('custom.send_email')}}"  method="post" id="send-user-email">
    {!! csrf_field() !!}
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Send Emails</h3>
        </div>

        <div class="box-body">
            <div class="form-group">
                <label>Users</label>
                <select class="form-control select2 select2-hidden-accessible" multiple="" name="users_id[]"
                        data-placeholder="Select a State" style="width: 100%;" tabindex="-1" aria-hidden="true">
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} ({{$user->email}})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- text input -->
            <div class="form-group">
                <label>Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Enter subject ...">
            </div>

            <!-- textarea -->
            <div class="form-group">
                <label>Message</label>
                <textarea class="form-control" rows="3" name="message" placeholder="Enter message..."></textarea>
            </div>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
<script src="/admin-constructor/AdminLTE-2.3.7/plugins/select2/select2.full.min.js"></script>
<link rel="stylesheet" href="/admin-constructor/AdminLTE-2.3.7/plugins/select2/select2.min.css">

<script>
	$(function () {
		$('#send-user-email').submit(function(){
			$.post('',$(this).serialize(),function(data){

            })
        })
		$(".select2").select2();
	})
</script>