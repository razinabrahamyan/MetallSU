<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: DejaVu Sans;
        }
        thead{
            background: #2D4154;
            color: white;
        }
        th{
            font-weight: bold;
            font-size: 15px;
            border-top: 1px solid #dee2e6;
            padding: 5px;
            text-align: inherit;
        }
        tr.even{
            background: #eaeaea;
        }
        td{
            padding: .5rem;
            border-top: 1px solid #dee2e6;
            font-size: 14px;
        }
        thead th{
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }
        tfoot th{
            border-top: 1px solid #73787e;
        }
        table{
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<p>{{ $title }}</p>
<table>
    <thead>
        <tr>
            <th>#</th>
        @foreach($tableColumns as $column)
            <th>
                {{$column->title}}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody >
    @foreach($rows as $row)
        <tr class="{{$loop->iteration%2 === 0 ?'even': ' '}}">
            <th >{{$loop->iteration}}</th>
            @foreach($row as $item)
                <td>
                    {{$item->value}}
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            @foreach($tableColumns as $column)
                <th>

                    {{$column->title}}
                </th>
            @endforeach
        </tr>
    </tfoot>
</table>
<p style="text-align: right">{{$time}}</p>
</body>
</html>
