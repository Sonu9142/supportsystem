<h1>

user list


</h1>

<table border="1px">
    <tr>
        <td>id</td>
        <td>name</td>
        <td>email</td>
        <td>phone</td>
        <td>role</td>
    </tr>
    @foreach($user as $users)
    <tr>
        <td>{{ $users->id }}</td>
        <td>{{ $users->name }}</td>
        <td>{{ $users->email }}</td>
        <td>{{ $users->phone }}</td>
        <td>{{ $users->role }}</td>
    </tr>
@endforeach

</table>