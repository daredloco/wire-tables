<div>
    <div class="@if($responsiveTable) table-responsive @endif">
        <table class="table {{ $tableClasses }}">
            <thead>
                <tr>
                    @foreach($columns as $column)
                    <th scope="col">{{ $column['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $rowIdx => $row)
                    <tr class="">
                        @foreach($row as $colIdx => $colContent)
                            @if($firstBold && $colIdx == 0)
                                <th scope="row">{{ $colContent }}</th>
                            @else      
                                <td scope="row">{{ $colContent }}</td>
                            @endif
                        @endforeach
                        @if(!is_null($editRoute) || !is_null($deleteRoute))
                            <td scope="row">@if(!is_null($editRoute)) <a href="{{ route($editRoute, [$modelInRoute => $ids[$rowIdx]]) }}">Edit</a> @endif @if(!is_null($deleteRoute)) <a href="{{ route($deleteRoute, [$modelInRoute => $ids[$rowIdx]]) }}">Delete</a> @endif</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(!is_null($createRoute))
    <div class="mt-3">
        <a href="{{ route($createRoute) }}" class="btn btn-success text-white">{{ __('Create') }}</a>
    </div>
    @endif
    
</div>