<table>
    <tr>
        <td>
            Dear {{$korespondensi->tender_rekanan->rekanan->name}},
        </td>
    </tr>
    <tr>
        <td>
            Terlampir <strong>{{$title}}</strong>. Demikian disampaikan dan terima kasih.<br/>
            Link Login : cpms.ciputragroup.com:81/login
        </td>
    </tr>
    <tr>
        <td>
            <br/>
            Salam,
        </td>
    </tr>
    <tr>
        <td>
            {{strtoupper($project_pt->pt->name)}}
        </td>
    </tr>
    <tr>
        <td>
            {{$project_pt->pt->address}}
        </td>
    </tr>
</table>
