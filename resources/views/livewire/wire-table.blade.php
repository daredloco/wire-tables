<div>
    <div class="@if($responsiveTable) table-responsive @endif">
        <table class="table {{ $tableClasses }}">
            <thead>
                <tr>
                    @foreach($columns as $column)
                    <th scope="col">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr class="">
                        @foreach($row as $colIdx => $colContent)
                            @if($firstBold && $colIdx == 0)
                                <th scope="row">{{ $colContent }}</th>
                            @else
                                <td scope="row">{{ $colContent }}</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>