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
                @foreach($rows as $row)
                    <tr class="">
                        @foreach($row as $colIdx => $colContent)
                            @if($firstBold && $colIdx == 0)
                                {{ dd($column, $colContent, $row) }}
                                @if($column['cast'] == \Helvetiapps\WireTables\Enums\Casts::Boolean)
                                    <th scope="row">{{ $colContent == true ? __('True') : __('False') }}</th>
                                @elseif($column['cast'] == \Helvetiapps\WireTables\Enums\Casts::Numeric)
                                    <th scope="row">{{ round($colContent,2) }}</th>
                                @elseif($column['cast'] == \Helvetiapps\WireTables\Enums\Casts::Date)
                                    <th scope="row">{{ (new \Carbon\Carbon($colContent))->format('d/m/Y') }}</th>
                                @elseif($column['cast'] == \Helvetiapps\WireTables\Enums\Casts::Currency)
                                    <th scope="row">R${{ number_format($colContent,2,',','.') }}</th>
                                @else
                                    <th scope="row">{{ $colContent }}</th>
                                @endif
                            @else
                                @if($column['cast'] == \Helvetiapps\WireTables\Enums\Casts::Boolean)
                                    <td scope="row">{{ $colContent == true ? __('True') : __('False') }}</td>
                                @elseif($column['cast'] == \Helvetiapps\WireTables\Enums\Casts::Numeric)
                                    <td scope="row">{{ round($colContent,2) }}</td>
                                @elseif($column['cast'] == \Helvetiapps\WireTables\Enums\Casts::Date)
                                    <td scope="row">{{ (new \Carbon\Carbon($colContent))->format('d/m/Y') }}</td>
                                @elseif($column['cast'] == \Helvetiapps\WireTables\Enums\Casts::Currency)
                                    <td scope="row">R${{ number_format($colContent,2,',','.') }}</td>
                                @else
                                    <td scope="row">{{ $colContent }}</td>
                                @endif
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>