<!DOCTYPE html>
<html>

<head>
    <title>{{tr('users')}}</title>
</head>
<style type="text/css">

    table{
        font-family: arial, sans-serif;
        border-collapse: collapse;
    }

    .first_row_design{
        background-color: #187d7d;
        color: #ffffff;
    }

    .row_col_design{
        background-color: #cccccc;
    }

    th{
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        font-weight: bold;

    }

    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;

    }
    
</style>

<body>

    <table>

        <!-- HEADER START  -->

        <tr class="first_row_design">

            <th>{{tr('s_no')}}</th>

            <th>{{tr('name')}}</th>

            <th>{{tr('email')}}</th>

            <th>{{tr('mobile')}}</th>

            <th>{{tr('status')}}</th>

            <th>{{tr('verify')}}</th>
        </tr>

        <!--- HEADER END  -->

        @foreach($data as $i => $user)

            <tr @if($i % 2 == 0) class="row_col_design" @endif >

                <td>{{$i+1}}</td>

                <td>{{$user->name ?: tr('not_available')}}</td>

                <td>{{$user->email ?? ""}}</td>

                <td>{{$user->mobile ?? "-"}}</td>

                <td>
                    @if($user->status == USER_APPROVED)

                        {{ tr('approved') }}

                    @else

                        {{ tr('declined') }}
                    @endif
                </td>

                <td>
                    @if($user->is_email_verified == USER_EMAIL_NOT_VERIFIED)

                        {{ tr('pending') }}

                    @else

                        {{ tr('verified') }}

                    @endif
                </td>
              
            </tr>

        @endforeach
    </table>

</body>

</html>