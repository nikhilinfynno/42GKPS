
@foreach ($options as $option)
    @php 
        $selected = '';
        if(isset($selected_condition['column'])){
            $columnName = $selected_condition['column'];
            $columnValue = $selected_condition['value'];
             
            if($option->$columnName == $columnValue){
                $selected = 'selected';
            }
        }
    @endphp
    <option {{$selected}} value="{{$option->$value_variable}}">{{$option->$label_variable}}</option>
@endforeach