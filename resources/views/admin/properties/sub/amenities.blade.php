
<a href="{{ route('properties-group-amenities'.'.create', [$data->id]) }}" class="btn btn-info"><i class="fa fa-plus"></i> Add Amenity Group </a>
<br>
<div class="alert"></div>
<table  class="table table-bordered table-striped">
<thead>
    <tr>
        <th>#</th>
        
        <th>Group Name</th>
        <th>Status</th>
        <th>Sorting</th>
        <th>Image</th>
        <th>Amenity </th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
        @php $sno=1; $property_id=$data->id;@endphp
    @foreach(App\Models\PropertyAmenityGroup::where("property_id",$data->id)->get() as $client)
        <tr>
           
            <td>{{ $sno++ }}</td>

       
            <td>{{ $client->name }}</td>
            
            <td>
                @if($client->status == "true")
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-times text-danger"></i>
                @endif
            </td>
            <td>{{ $client->sorting }}</td>
            
            <td>
                @if($client->image)
                    <img src="{{ asset($client->image) }}" width="100"> 
                @endif
            </td>
            <td>
                @foreach(App\Models\PropertyAmenity::where("property_amenity_id",$client->id)->get() as $c)
                <span class="badge badge-warning">{{ $c->name}}</span>
                @endforeach
                <hr>
                  <a href="{!! route('properties-amenities', [$property_id,$client->id]) !!}" class="btn btn-success btn-xs raw-margin-right-8 "><i
                            class="fa fa-plus"></i> Amenities</a>
            </td>
            <td>
                <a href="{!! route('properties-group-amenities'.'.edit', [$property_id,$client->id]) !!}" class="btn btn-outline-success btn-xs raw-margin-right-8 btn-block"><i
                            class="fa fa-pencil-alt"></i> Edit</a>
                @if($client->status == "true")
                    <a href="{!! route('properties-group-amenities'.'.deactive', [$property_id,$client->id]) !!}" class="btn btn-warning btn-xs raw-margin-right-8 btn-block"><i
                            class="fa fa-times"></i> Deactive</a>
                @else
                    <a href="{!! route('properties-group-amenities'.'.active', [$property_id,$client->id]) !!}" class="btn btn-warning btn-xs raw-margin-right-8 btn-block"><i
                            class="fa fa-check"></i> Active</a>
                @endif
                <br>
           
               
                    <a href="{{ route('properties-group-amenities'.'.destroy', [$property_id,$client->id]) }}" class="btn btn-outline-danger btn-xs raw-margin-right-8  btn-block"
                            onclick="return confirm('Are you sure you want to delete this Amenities Group, Destroy All child data?')"><i
                                class="fa fa-trash"></i> Delete
                    </a>
               
            </td>
        </tr>
    @endforeach
</tbody>

</table>