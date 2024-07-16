<form id="date">

    <label>
        <input name="startDate" type="date"/>
        <span>{{ __('Start Date') }}</span>
    </label>
    <label>
        <input name="endDate" type="date"/>
        <span>{{ __('End Date') }}</span>
    </label>
    <input type="submit" class="btn btn-primary" value="Submit">
</form>

@push('styles')
<style>
form#date {

}
form#date label {
  position: relative;
  margin-left: 10px;
  margin-right: 10px;
}
form#date label > input {
  position: relative;
  background-color: transparent;
  border: none;
  border-bottom: 1px solid #9e9e9e;
  border-radius: 0;
  outline: none;
  height: 45px;
  font-size: 16px;
  margin: 0 0 30px 0;
  padding: 0;
  box-shadow: none;
  box-sizing: content-box;
  transition: all .3s;
}
form#date label > input:valid + span {
  transform: translateY(-25px) scale(0.8);
  transform-origin: 0;
}
form#date label > input:valid {
  border-bottom: 1px solid #3F51B5;
  box-shadow: 0 1px 0 0 #3F51B5;
}
form#date label > span {
  color: #9e9e9e;
  position: absolute;
  top: 0;
  left: 0;
  font-size: 16px;
  cursor: text;
  transition: .2s ease-out;
}
form#date label > input:focus + span {
  transform: translateY(-25px) scale(0.8);
  transform-origin: 0;
  color: #3F51B5;
}
form#date label > input:focus {
  border-bottom: 1px solid #3F51B5;
  box-shadow: 0 1px 0 0 #3F51B5;
}
</style>
@endpush
