<table>
    <tr>
        <td>
            Dear {{$user->user_name}},
        </td>
    </tr>
    <tr>
        <td>
            Berikut ini email pemberitahuan untuk permintaan Approval {{$title}}:
        </td>
    </tr>
    <tr>
        <td>
            <br/>
            Nama {{$title}} : {{$name}}.
        </td>
    </tr>
    <tr>
        <td>
            <br/>
             klik link berikut ini untuk melihat detail : 
        </td>
    </tr>
    <tr>
        <td>
            <a href="{{$link}}"> Link Approval</a>
        </td>
    </tr>
    <tr>
        <td>
            Demikian disampaikan dan terima kasih.
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
